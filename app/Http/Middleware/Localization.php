<?php

namespace App\Http\Middleware;

use App\Repositories\WebsiteRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentWebsiteMetas = WebsiteRepository::getInstance()->getMetas();
        $websiteLanguages = Arr::get($currentWebsiteMetas, 'languages_list');
        $headerLocale = $request->header('locale');

        if ($headerLocale && in_array($headerLocale, $websiteLanguages)) {
            App::setLocale($headerLocale);
        }

        return $next($request);
    }
}
