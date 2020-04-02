<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\EnumCast;
use App\Enums\UserRoleEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    const DELETED_AT = 'blocked_at';

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
        'role' => EnumCast::class. ':' . UserRoleEnum::class,
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'owner_id');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->in([UserRoleEnum::ADMIN, UserRoleEnum::SUPERADMIN]);
    }

    /**
     * @return bool
     */
    public function isSuperadmin()
    {
        return $this->role->is(UserRoleEnum::SUPERADMIN);
    }
}
