<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    protected $fillable = ['user_id', 'website_id', 'name', 'type', 'type_id', 'layout_id', 'content', 'params'];

    protected $casts = [
        'content' => 'array',
        'params' => 'array',
    ];

    const TYPE_LAYOUT = 'layout';
    const TYPE_BLOCK = 'block';
    const TYPE_POST = 'post';
    const TYPE_CATEGORY = 'category';

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

    public static function types(): array
    {
        return [self::TYPE_LAYOUT, self::TYPE_BLOCK, self::TYPE_POST, self::TYPE_CATEGORY];
    }

    public static function defaultType(): string
    {
        return self::TYPE_BLOCK;
    }

    public static function saveFileTypes(): array
    {
        return [self::TYPE_POST, self::TYPE_CATEGORY];
    }
}
