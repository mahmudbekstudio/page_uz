<?php

namespace App\Models\Traits;

use App\Models\Scopes\WebsiteScope;
use App\Repositories\WebsiteRepository;

trait WebsiteAddScopeTrait
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function websiteAddScope()
    {
        static::addGlobalScope(new WebsiteScope);
    }

    protected static function setWebsiteAttr()
    {
        static::saving(function ($model) {
            $model->website_id = $model->website_id ? $model->website_id : WebsiteRepository::getInstance()->getCurrent()->id;
        });
    }
}
