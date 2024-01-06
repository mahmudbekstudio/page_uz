<?php

namespace App\Providers;

use App\Helpers\GlobalVariable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use URL;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GlobalVariable::class, function (Application $app) {
            return new GlobalVariable();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!App::environment(['local'])) {
            URL::forceScheme('https');
        }
    }
}
