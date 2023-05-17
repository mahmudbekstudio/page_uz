<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    /**
     * @var array
     */
    protected $fillable = ['website_id', 'user_id', 'meta_key', 'meta_value', 'meta_format'];

    protected static function booted()
    {
        parent::booted();
        self::websiteAddScope();
    }

    public static function boot()
    {
        parent::boot();
        static::setWebsiteAttr();
        static::setUserAttr();
    }
}
