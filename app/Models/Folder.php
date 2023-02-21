<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Folder extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    protected $fillable = ['website_id', 'user_id', 'parent_id', 'name', 'path'];

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

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(Folder::class, 'id', 'parent_id');
    }
}
