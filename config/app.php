<?php

use Illuminate\Support\Facades\Facade;
use App\Models\User;
use App\Models\Website;
use App\Models\Type;
use App\Models\Feature;

return [
    'version' => '1',
    'main_website' => env('MAIN_WEBSITE', 'page.uz'),
    'default_locale' => 'en',
    'locale_list' => ['en', 'ru', 'uz'],
    'special_routes' => ['administrator', 'cabinet', 'panel'],
    'open_when_website_temporary_closed' => ['admin', 'api/admin'],
    'route_rules' => [
        'number' => '[0-9]+',
        'name' => '[a-z0-9_\-]+',
        'domain' => '[a-z0-9_\-\.]+',
    ],
    //'website_id' => 0,
    'min_password_length' => 6,
    'max_upload_size' => 5000,// Kb
    'allow_extension' => [
        '3gp', 'avi', 'flv', 'mov', 'mp4', 'mpg', 'mpeg', 'wmv',
        'jpg', 'png', 'gif', 'jpeg', 'ico', 'svg',
        'doc', 'docx', 'pdf', 'txt', 'xls', 'xlsx', 'rtf', 'xml',
        'mp3', 'wav', 'mid', 'midi', 'mpa', 'ogg',
        'rar', 'zip', 'tar.gz',
        'css'
    ],
    'status' => [
        'user' => [
            User::STATUS_NOT_CONFIRMED => 'not_confirmed',
            User::STATUS_ACTIVE => 'active',
            User::STATUS_BLOCKED => 'blocked',
        ],
        'website' => [
            Website::STATUS_NOT_CONFIRMED => 'not_confirmed',
            Website::STATUS_ACTIVE => 'active',
            Website::STATUS_BLOCKED => 'blocked',
            Website::STATUS_TEMPORARILY_CLOSED => 'temporarily_closed',
            Website::STATUS_FORBIDDEN => 'forbidden',
            //Website::STATUS_REDIRECT => 'redirect',
            Website::STATUS_CLOSED => 'closed',
        ],
    ],
    'userRoles' => [
        User::ROLE_SUPER_ADMIN,
        User::ROLE_ADMIN,
        User::ROLE_MANAGER,
        User::ROLE_PUBLISHER,
        User::ROLE_USER
    ],
    'manage' => [
        'user' => [
            User::ROLE_SUPER_ADMIN => [
                'create' => [
                    User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'read' => [
                    User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'update' => [
                    User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'delete' => [
                    User::ROLE_SUPER_ADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ]
            ],
            User::ROLE_ADMIN => [
                'create' => [
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'read' => [
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'update' => [
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'delete' => [
                    User::ROLE_MANAGER,
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ]
            ],
            User::ROLE_MANAGER => [
                'create' => [
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'read' => [
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'update' => [
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ],
                'delete' => [
                    User::ROLE_PUBLISHER,
                    User::ROLE_USER
                ]
            ],
            User::ROLE_PUBLISHER => [
                'create' => [],
                'read' => [],
                'update' => [],
                'delete' => []
            ],
            User::ROLE_USER => [
                'create' => [],
                'read' => [],
                'update' => [],
                'delete' => []
            ]
        ]
    ],
    'timeFormat' => [
        'full' => 'YYYY-MM-DD HH:mm:ss',
        'date' => 'YYYY-MM-DD',
        'time' => 'HH:mm:ss',
    ],
    'parentPageDeepLimit' => 10,
    'main_page_type' => 'page',
    'main_post_type' => 'post',
    'main_category_type' => 'category',
    //
    'theme' => [
        'components' => [
            'bootstrap' => [
                '5.3' => '2', '5.2' => '3', '5.1' => '3', '5.0' => '2',
                '4.6' => '2', '4.5' => '3', '4.4' => '1', '4.3' => '1', '4.2' => '1', '4.1' => '3', '4.0' => '0',
                '3.4' => '1', '3.3' => '7',
            ],
            'swiper_js' => [
                '11' => '0.5',
                '10' => '3.1',
                '9' => '4.1',
                '8' => '4.7',
                '7' => '4.1',
                '6' => '8.4',
                '5' => '4.5',
                '4' => '5.1',
                '3' => '4.2',
                '2' => '7.6',
            ],
        ],
        'cdn' => [
            'bootstrap' => [
                'css' => 'https://cdn.jsdelivr.net/npm/bootstrap@{VERSION}/dist/css/bootstrap.min.css',
                'js' => 'https://cdn.jsdelivr.net/npm/bootstrap@{VERSION}/dist/js/bootstrap.min.js'
            ],
            'swiper_js' => [
                'css' => 'https://cdn.jsdelivr.net/npm/swiper@{VERSION}/swiper-bundle.min.css',
                'js' => 'https://cdn.jsdelivr.net/npm/swiper@{VERSION}/swiper-bundle.min.js'
            ]
        ],
        'default' => [
            'css' => [
                'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
                'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js',
                //https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/scripts/verify.min.js
            ],
            /*'template' => [
                'layout' => [
                    'content' => [],
                    'params' => [],
                ],
            ],*/
        ],
    ],
    'format' => [
        'date' => 'Y-m-d',
        'time' => 'H:i:s'
        /*'date' => [//Y, y      F, m, M         d, D
            'Y-m-d',
            'd-m-Y',
        ],
        'time' => [
            'H:i:s',
            'H:i',
            'h:i:s A',
            'h:i A',
        ],*/
    ],
    'template' => [
        'except_fields' => ['childOf', 'template', 'parent', 'status', 'routeName'],
    ],
    'feature_types' => [
        ...Type::pageTypes(),
        ...Feature::typesList(),
    ],
    'default_feature_type' => Feature::TYPE_LAYOUT,
    'feature_types_list' => [
        Feature::TYPE_LAYOUT => [
            ['id' => 0, 'title' => 'words.feature.block'],
            /*'slider',
            'header',
            'cover',
            'footer'*/
        ],
    ],
    'type' => [
        'all' => Type::types(),
        'page' => Type::pageTypes(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Gecche\Multidomain\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        App\Providers\HelperServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
    ])->toArray(),

];
