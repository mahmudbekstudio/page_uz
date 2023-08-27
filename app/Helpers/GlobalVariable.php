<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class GlobalVariable
{
    private $vars = [];

    public function set(string $key, $value)
    {
        Arr::set($this->vars, $key, $value);
    }

    public function has(string $key)
    {
        return Arr::has($this->vars, $key);
    }

    public function get($key, $defaultValue = null)
    {
        return Arr::get($this->vars, $key, $defaultValue);
    }
}
