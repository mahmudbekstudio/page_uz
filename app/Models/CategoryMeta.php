<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryMeta extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    protected $fillable = ['website_id', 'user_id', 'category_id', 'meta_format', 'meta_key', 'meta_value'];

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
