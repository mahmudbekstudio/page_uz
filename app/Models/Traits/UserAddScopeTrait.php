<?php

namespace App\Models\Traits;

use App\Models\Scopes\UserScope;

trait UserAddScopeTrait
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function userAddScope()
    {
        static::addGlobalScope(new UserScope);
    }

    protected static function setUserAttr()
    {
        static::saving(function ($model) {
            $model->user_id = $model->user_id ? $model->user_id : (int)auth()->id();
        });
    }
}
