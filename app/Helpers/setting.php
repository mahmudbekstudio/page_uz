<?php

if (!function_exists('isProd')) {
    /**
     * Check if environment is prod return true
     *
     * @return bool
     */
    function isProd(): bool
    {
        return app()->environment(['prod', 'production']);
    }
}

if (!function_exists('getCurrentWebsite')) {
    /**
     * Get current website
     *
     * @return \App\Models\Website
     */
    function getCurrentWebsite(): \App\Models\Website
    {
        $currentWebsite = \App\Repositories\WebsiteRepository::getInstance()->getCurrent();

        return $currentWebsite ?: errorPageNotFound();
    }
}

if (!function_exists('getCurrentWebsiteId')) {
    /**
     * Get current website id
     *
     * @return string
     */
    function getCurrentWebsiteId(): string
    {
        $currentWebsite = \App\Repositories\WebsiteRepository::getInstance()->getCurrent();

        return $currentWebsite ? $currentWebsite->id : 0;
    }
}

if (!function_exists('getLang')) {
    /**
     * Get language code
     *
     * @param string $lang
     * @return string
     */
    function getLang(string $lang = ''): string
    {
        return isLang($lang) ? $lang : app()->getLocale();
    }
}

if (!function_exists('isLang')) {
    /**
     * Check language code
     *
     * @param string $lang
     * @return string
     */
    function isLang(string $lang = ''): string
    {
        return in_array($lang, config('app.locale_list'));
    }
}
