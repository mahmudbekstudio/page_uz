<?php

namespace App\Models;

use App\Models\Traits\WebsiteAddScopeTrait;
use App\Repositories\WebsiteRepository;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, WebsiteAddScopeTrait, HasRoles, CanResetPassword;

    const STATUS_NOT_CONFIRMED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_PUBLISHER = 'publisher';
    const ROLE_USER = 'user';

    const GUARD_NAME = 'api';

    public function guardName()
    {
        return self::GUARD_NAME;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'website_id',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::setWebsiteAttr();
    }

    protected static function booted()
    {
        parent::booted();
        self::websiteAddScope();
    }

    /**
     * Metas
     *
     * @return HasMany
     */
    public function metas(): HasMany
    {
        return $this->hasMany(UserMeta::class);
    }

    /**
     * Setting password field
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        if(!empty($password)) {
            $this->attributes['password'] = Hash::make($password);
        }
    }

    /**
     * Get user role name
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getEmailForPasswordReset()
    {
        return 'website-' . $this->website_id . '__' . $this->email;
    }
}
