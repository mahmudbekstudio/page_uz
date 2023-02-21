<?php
if(!function_exists('errorForbidden')) {
    /**
     * Forbidden
     */
    function errorForbidden()
    {
        abort(403, __('error.403.title'));
    }
}

if(!function_exists('errorPageNotFound')) {
    /**
     * Page Not Found
     */
    function errorPageNotFound()
    {
        abort(404, __('error.404.title'));
    }
}

if(!function_exists('errorPageExpired')) {
    /**
     * Page Expired
     */
    function errorPageExpired()
    {
        abort(419, __('error.419.title'));
    }
}

if(!function_exists('errorManyRequest')) {
    /**
     * Too many requests
     */
    function errorManyRequest()
    {
        abort(429, __('error.429.title'));
    }
}

if(!function_exists('errorWebsiteBlocked')) {
    /**
     * Website blocked
     */
    function errorWebsiteBlocked()
    {
        abort(451, __('error.451.title'));
    }
}

if(!function_exists('errorWentWront')) {
    /**
     * Something went wrong
     */
    function errorWentWront()
    {
        abort(500, __('error.500.title'));
    }
}

if(!function_exists('errorServiceUnavailable')) {
    /**
     * Service Unavailable
     */
    function errorServiceUnavailable()
    {
        abort(503, __('error.503.title'));
    }
}
