<?php

namespace App\Models;

use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeRouteStructure extends Model
{
    use BelongsToWebsite, HasFactory, WebsiteAddScopeTrait;

    protected $fillable = ['website_id', 'type_id', 'parent_id', 'params', 'structure'];

    protected $casts = [
        'structure' => 'array',
        'params' => 'array',
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
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
