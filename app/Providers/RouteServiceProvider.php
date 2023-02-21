<?php

namespace App\Providers;

use App\Models\Website;
use App\Repositories\WebsiteRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        Route::pattern('id', config('app.route_rules.number'));
        Route::pattern('name', config('app.route_rules.name'));

        $this->initWebsite(app()->domain());

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace . '\Api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace . '\Web')
                ->group(base_path('routes/web.php'));
            Route::prefix('api/admin')
                ->middleware('api')
                ->namespace($this->namespace . '\Admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Init website
     *
     * @param $domain
     */
    protected function initWebsite($domain) {
        if(!$domain) {
            return;
        }

        /** @var $website Website */
        $website = WebsiteRepository::getInstance()->getByDomain($domain);

        if (!$website || in_array($website->status, [Website::STATUS_NOT_CONFIRMED, Website::STATUS_CLOSED])) {
            errorPageNotFound();
        }

        if ($website->status === Website::STATUS_BLOCKED) {
            errorWebsiteBlocked();
        }

        if ($website->status === Website::STATUS_TEMPORARILY_CLOSED) {
            errorServiceUnavailable();
        }

        if ($website->status === Website::STATUS_FORBIDDEN) {
            errorForbidden();
        }

        WebsiteRepository::getInstance()->setCurrent($website->id);

        /*if ($website->status === Website::STATUS_REDIRECT) {
            header('Location: ' . WebsiteRepository::getInstance()->getMetaValue('redirect'));
            exit;
        }*/
    }
}
