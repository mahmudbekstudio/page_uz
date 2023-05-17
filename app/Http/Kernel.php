<?php

namespace App\Http;

use App\Http\Middleware\Localization;
use App\Models\Website;
use Gecche\Multidomain\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Gecche\Multidomain\Foundation\Bootstrap\DetectDomain;
use Dotenv\Dotenv;

class Kernel extends HttpKernel
{
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);
        $app->beforeBootstrapping(DetectDomain::class, function ($app) {
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();

            $connection = new \PDO("{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};port={$_ENV['DB_PORT']}", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $result = $connection
                ->query("SELECT * FROM `websites` WHERE `domain`='" . $_SERVER['SERVER_NAME'] . "'")
                ->fetchAll(\PDO::FETCH_ASSOC);

            if(!empty($result)) {
                $result = $result[0];
                $domainId = $result['domain_id'] ?: $result['id'];

                if ($result['type'] == Website::TYPE_MAIN) {
                    $_ENV['current-website'] = $result;
                    $this->initDotEnv($app, $domainId);
                } elseif(in_array($result['type'], [Website::TYPE_REDIRECT, Website::TYPE_ALIAS])) {
                    $websiteResult = $connection
                        ->query("SELECT * FROM `websites` WHERE (`id`='" . $domainId . "' OR `domain_id`='" . $domainId . "') AND `type`='" . Website::TYPE_MAIN . "'")
                        ->fetchAll(\PDO::FETCH_ASSOC);

                    if(!empty($websiteResult)) {
                        if ($result['type'] == Website::TYPE_REDIRECT) {
                            $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
                            header('Location: ' . $protocol . '://' . $websiteResult[0]['domain']);
                            exit;
                        }

                        $result['domain'] = $websiteResult[0]['domain'];
                        $_ENV['current-website'] = $result;
                        $this->initDotEnv($app, $domainId);
                    } else {
                        errorPageNotFound();
                    }
                }

            }
        });
    }

    private function initDotEnv($app, $domainId)
    {
        $dotenv = Dotenv::createMutable(storage_path('domains/env'), '.env.' . $domainId);
        $dotenv->safeLoad();

        //$app->setEnvironmentFile('.env.' . $domainId);
        $app->useStoragePath(storage_path('domains/' . $domainId));
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        Localization::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
    ];
}
