<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Website
 * @package App\Models
 *
 * @property $id
 * @property $status
 * @property $domain
 * @property $type
 * @property $domain_id
 */
class Website extends Model
{
    use HasFactory;

    const STATUS_NOT_CONFIRMED = 0;         // website new created
    const STATUS_ACTIVE = 1;                // website confirmed and entered main config of website
    const STATUS_BLOCKED = 2;               // website blocked by admin of platform
    const STATUS_TEMPORARILY_CLOSED = 3;    // website temporarily closed by website owner
    const STATUS_FORBIDDEN = 4;             // website confirmed but not entered config of website
    //const STATUS_REDIRECT = 5;              // Website redirected to another url
    const STATUS_CLOSED = 6;                // If not purchased close website

    const TYPE_MAIN = 1;
    const TYPE_ALIAS = 2;
    const TYPE_REDIRECT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'status', 'domain', 'type', 'domain_id'];

    /**
     * @return HasMany
     */
    public function metas(): HasMany
    {
        return $this->hasMany(WebsiteMeta::class);
    }
}
