<?php

namespace App\Console\Commands\Frontend;

use Illuminate\Console\Command;

class BuildFrontend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:frontend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build frontend (routes, translations, settings etc.)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('platform:frontend:routes');
        $this->call('platform:frontend:translations');
        $this->call('platform:frontend:config');
    }
}
