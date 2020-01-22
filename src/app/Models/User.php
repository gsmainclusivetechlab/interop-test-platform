<?php

namespace App\Models;

use App\Models\Traits\HasEnums;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasEnums;
    use Notifiable;
    use SoftDeletes;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super-admin';

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
        'deleted_at' => 'datetime',
    ];

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
        return in_array($this->role, [static::ROLE_ADMIN, static::ROLE_SUPER_ADMIN]);
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array($this->role, [static::ROLE_SUPER_ADMIN]);
    }

}
