<?php

namespace App\Console\Commands\Frontend;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;

class BuildTranslations extends Command
{
    /**
     * Locales that is allowed for export
     * @var array
     */
    protected $allowed = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:frontend:translations';

    /**
     * Name of the created file.
     *
     * @var string
     */
    protected $targetFilename = 'translations.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build translations for frontend (put all translations into a .json file).';

    /**
     * BuildTranslations constructor.
     *
     * @param Application $app
     * @param Filesystem $filesystem
     */
    public function __construct(Application $app, Filesystem $filesystem)
    {
        parent::__construct($filesystem);

        $this->allowed = config('app.locale_list');
    }

    /**
     * Export data into json file.
     *
     * @return array
     */
    public function export(): array
    {
        return $this->getTranslations();
    }

    /**
     * Get translation.
     *
     * @return array
     */
    protected function getTranslations()
    {
        $translations = [];
        $namespaces = $this->getTranslatorNamespaces();
        foreach ($namespaces as $namespace => $path) {
            foreach ($this->locales($path) as $index => $locale) {
                $name = basename($locale);
                if (in_array($name, $this->allowed)) {
                    if (!array_key_exists($name, $translations)) {
                        $translations[$name] = [];
                    }
                    $translationsGroups = $this->getTranslationsGroups($locale, $namespace);
                    $translations[$name] = array_merge($translations[$name], $translationsGroups);
                }
            }
        }

        return $translations;
    }

    /**
     * Get translation groups.
     *
     * @param $path
     * @param string $namespace
     * @return array
     */
    protected function getTranslationsGroups($path, $namespace = ''): array
    {
        $files = $this->filesystem->files($path);

        $this->info($namespace);

        $groups = [];
        foreach ($files as $index => $file) {
            $name = $this->filesystem->name($file);
            $locale = last(explode(DIRECTORY_SEPARATOR, $path));

            if ($namespace) {
                $name = $namespace . '::' . $name;
            }

            $groups[$name] = trans($name, [], $locale);
        }

        return $groups;
    }

    /**
     * Get available locale directories.
     *
     * @param $path
     * @return array
     */
    protected function locales($path = ''): array
    {
        if (!$path) {
            $path = app()->langPath();
        }
        $locales = $this->filesystem->directories($path);

        $names = collect($locales)->map(function ($folder) {
            return basename($folder);
        })->implode(',');

        $this->info("Found locales {$names} ...");

        return $locales;
    }

    /**
     * Gets all namespaces used with translations path
     *
     * @return array
     */
    protected function getTranslatorNamespaces(): array
    {
        $loader = resolve('translation.loader');

        $namespaces = $loader->namespaces();
        $namespaces[''] = app()->langPath();

        return $namespaces;
    }
}
