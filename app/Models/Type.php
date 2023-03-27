<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory, BelongsToWebsite, BelongsToUser, WebsiteAddScopeTrait, UserAddScopeTrait;

    /**
     * @var array
     */
    protected $fillable = ['status', 'name', 'type', 'has_parent', 'child_of', 'structure', 'fields'];

    protected $casts = [
        'structure' => 'array',
        'fields' => 'array',
    ];

    protected $hidden = ['website_id', 'user_id'];

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
        return [self::TYPE_POST, self::TYPE_CATEGORY];
    }

    public static function defaultType(): string
    {
        return self::TYPE_POST;
    }
}
