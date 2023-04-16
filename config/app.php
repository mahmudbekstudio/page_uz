<?php

use Illuminate\Support\Facades\Facade;
use App\Models\User;
use App\Models\Website;

return [
    'version' => '1',
    'main_website' => env('MAIN_WEBSITE', 'page.uz'),
    'locale_list' => ['en', 'ru', 'uz'],
    'route_rules' => [
        'number' => '[0-9]+',
        'name' => '[a-z0-9_\-]+',
        'domain' => '[a-z0-9_\-\.]+',
    ],
    'website_id' => 0,
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
    'timeFormat' => [
        'full' => 'YYYY-MM-DD HH:mm:ss',
        'date' => 'YYYY-MM-DD',
        'time' => 'HH:mm:ss',
    ],
    'parentPageDeepLimit' => 10,
    'main_page' => [
        'name' => 'page',
        'structure' => '[{"type": "tab", "title": "Main", "children": [{"type": "row", "children": [{"size": "6", "type": "col", "children": [{"name": "title", "type": "requiredTitle", "value": null, "events": [], "params": {"label": "Title", "valueType": "string", "validation": {"required": null}}, "disabled": false}]}, {"size": "6", "type": "col", "children": [{"name": "routeName", "type": "requiredRouteName", "value": null, "events": [], "params": {"label": "Route name", "valueType": "string", "validation": {"required": null, "routeName": null}}, "disabled": false}]}]}, {"type": "row", "children": [{"size": "3", "type": "col", "children": [{"name": "status", "type": "requiredStatus", "value": true, "events": [], "params": {"label": "Status", "valueType": "bool"}, "disabled": false}]}, {"size": "9", "type": "col", "children": [{"name": "parent", "type": "advancedParent", "value": 0, "events": [], "params": {"label": "Select parent", "valueType": "int"}, "disabled": false}]}]}, {"type": "row", "children": [{"size": "12", "type": "col", "children": [{"name": "content", "type": "editor", "value": null, "events": [], "params": {"label": "Content", "validation": []}, "disabled": false}]}]}]}, {"type": "tab", "title": "SEO", "children": [{"type": "row", "children": [{"size": "12", "type": "col", "children": [{"name": "seoKeyword", "type": "requiredSeoKeyword", "value": null, "events": [], "params": {"label": "SEO keywords", "valueType": "string"}, "disabled": false}, {"name": "seoDescription", "type": "requiredSeoDescription", "value": null, "events": [], "params": {"label": "SEO description", "valueType": "string"}, "disabled": false}]}]}]}, {"type": "tab", "title": "Advanced", "children": [{"type": "row", "children": [{"size": "12", "type": "col", "children": [{"name": "template", "type": "requiredTemplate", "value": 0, "events": [], "params": {"label": "Template", "valueType": "int"}, "disabled": false}]}]}, {"type": "row", "children": [{"size": "6", "type": "col", "children": [{"name": "publishStart", "type": "requiredPublishStart", "value": null, "events": [], "params": {"label": "Publish start date", "valueType": "string"}, "disabled": false}]}, {"size": "6", "type": "col", "children": [{"name": "publishEnd", "type": "requiredPublishEnd", "value": null, "events": [], "params": {"label": "Publish end date", "valueType": "string"}, "disabled": false}]}]}]}]',
        'fields' => '[{"name": "title", "type": "requiredTitle", "value": null, "events": [], "params": {"label": "Title", "valueType": "string", "validation": {"required": null}}, "disabled": false}, {"name": "routeName", "type": "requiredRouteName", "value": null, "events": [], "params": {"label": "Route name", "valueType": "string", "validation": {"required": null, "routeName": null}}, "disabled": false}, {"name": "status", "type": "requiredStatus", "value": true, "events": [], "params": {"label": "Status", "valueType": "bool"}, "disabled": false}, {"name": "parent", "type": "advancedParent", "value": 0, "events": [], "params": {"label": "Select parent", "valueType": "int"}, "disabled": false}, {"name": "content", "type": "editor", "value": null, "events": [], "params": {"label": "Content", "validation": []}, "disabled": false}, {"name": "seoKeyword", "type": "requiredSeoKeyword", "value": null, "events": [], "params": {"label": "SEO keywords", "valueType": "string"}, "disabled": false}, {"name": "seoDescription", "type": "requiredSeoDescription", "value": null, "events": [], "params": {"label": "SEO description", "valueType": "string"}, "disabled": false}, {"name": "template", "type": "requiredTemplate", "value": 0, "events": [], "params": {"label": "Template", "valueType": "int"}, "disabled": false}, {"name": "publishStart", "type": "requiredPublishStart", "value": null, "events": [], "params": {"label": "Publish start date", "valueType": "string"}, "disabled": false}, {"name": "publishEnd", "type": "requiredPublishEnd", "value": null, "events": [], "params": {"label": "Publish end date", "valueType": "string"}, "disabled": false}]',
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
