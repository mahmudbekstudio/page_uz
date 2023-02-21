<?php

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        dd(22222);
        if(!isset($parameters['domain'])) {
            $parameters['domain'] = $_SERVER['HTTP_HOST'];
        }

        return app('url')->route($name, $parameters, $absolute);
    }
}
