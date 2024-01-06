<?php

namespace App\Console;

use App\Console\Commands\Frontend\BuildConfig;
use App\Console\Commands\Frontend\BuildFrontend;
use App\Console\Commands\Frontend\BuildRoutes;
use App\Console\Commands\Frontend\BuildTranslations;
use Dotenv\Dotenv;
use Gecche\Multidomain\Foundation\Bootstrap\DetectDomain;
use Illuminate\Console\Scheduling\Schedule;
use Gecche\Multidomain\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BuildFrontend::class,
        BuildRoutes::class,
        BuildTranslations::class,
        BuildConfig::class,
    ];

    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);

        $app->beforeBootstrapping(DetectDomain::class, function ($app) {
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();
        });
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cache:prune-stale-tags')->hourly();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
