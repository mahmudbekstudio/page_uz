<?php

namespace App\Console\Commands\Frontend;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class BuildRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:frontend:routes';

    /**
     * Name of the created file.
     *
     * @var string
     */
    protected $targetFilename = 'routes.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build routes for frontend (put all routes into a .json file).';

    /**
     * Export data into json file.
     *
     * @return array
     */
    public function export(): array
    {
        $exceptPrefixKeys = ['debugbar.'];
        return $this->getRoutes()->filter(function ($value, $key) use ($exceptPrefixKeys) {
            return !Str::startsWith($key, $exceptPrefixKeys);
        })->toArray();
    }

    /**
     * Routes collection.
     *
     * @return static
     */
    private function getRoutes()
    {
        return collect(app(Router::class)->getRoutes()->getRoutesByName())
            ->map(function ($route) {
                return collect($route)
                    ->only(['uri', 'methods'])
                    ->put('domain', $route->domain());
            });
    }
}
