<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

/**
 * @mixin Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // use HasEnums;
    use Notifiable;
    use SoftDeletes;

    const DELETED_AT = 'blocked_at';

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPERADMIN = 'superadmin';

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'role',
        'email',
        'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return array
     */
    public static function getRoles()
    {
        return [
            static::ROLE_USER => __('User'),
            static::ROLE_ADMIN => __('Admin'),
            static::ROLE_SUPERADMIN => __('Superadmin'),
        ];
    }

    /**
     * @return string|null
     */
    public function getRoleNameAttribute()
    {
        return Arr::get($this->getRoles(), $this->role);
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array($this->role, [static::ROLE_ADMIN, static::ROLE_SUPERADMIN]);
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array($this->role, [static::ROLE_SUPERADMIN]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany(TestSession::class);
    }
}
