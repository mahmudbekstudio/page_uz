<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    protected $fillable = ['user_id', 'website_id', 'category_id', 'template_id', 'status', 'parent_id', 'type_id', 'url'];

    protected $casts = [
        'status' => 'boolean',
    ];

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

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function metas(): HasMany
    {
        return $this->hasMany(PostMeta::class);
    }

    public function route(): HasOne
    {
        return $this->hasOne(Route::class, 'parent_id')->where('type_id', $this->type_id);
    }
}
