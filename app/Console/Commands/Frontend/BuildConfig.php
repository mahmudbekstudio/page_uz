<?php

namespace App\Console\Commands\Frontend;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;

class BuildConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:frontend:config';

    /**
     * Name of the created file.
     *
     * @var string
     */
    protected $targetFilename = 'config.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build configs for frontend (put selected configs into a .json file).';

    /**
     * List of all configs that should be passed to frontend.
     *
     * @var array
     */
    protected $configs = [];

    /**
     * BuildConfig constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);

        $this->configs = config('frontend');
    }

    /**
     * Export data into json file.
     *
     * @return array
     */
    public function export(): array
    {
        return $this->getConfigs();
    }

    /**
     * Get all configs.
     *
     * @return array
     */
    protected function getConfigs(): array
    {
        $configs = [];

        foreach ($this->configs as $key => $config) {
            $key = is_string($key) ? $key : $config;
            Arr::set($configs, $key, config($config, $config));
        }

        return $configs;
    }
}
