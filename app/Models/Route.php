<?php

namespace App\Models;

use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use BelongsToWebsite, HasFactory, WebsiteAddScopeTrait;

    protected $fillable = ['website_id', 'name', 'parent_id', 'type_id'];

    protected static function booted()
    {
        parent::booted();
        self::websiteAddScope();
    }

    public static function boot()
    {
        parent::boot();
        static::setWebsiteAttr();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
