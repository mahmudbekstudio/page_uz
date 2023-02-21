<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToWebsite;
use App\Models\Traits\UserAddScopeTrait;
use App\Models\Traits\WebsiteAddScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use BelongsToWebsite, BelongsToUser, HasFactory, WebsiteAddScopeTrait, UserAddScopeTrait;

    protected $fillable = ['website_id', 'user_id', 'folder_id', 'name', 'extension', 'size'];

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

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
