<?php

namespace App\Repositories\Traits;

trait StaticInstance
{
    /**
     * Save object instance
     *
     * @var mixed
     */
    private static $_self = false;

    /**
     * Get saved instance
     *
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public static function getInstance()
    {
        if(!self::$_self) {
            self::$_self = app(self::class);
        }

        return self::$_self;
    }
}
