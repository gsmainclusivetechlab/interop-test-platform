<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * @var array
     */
    protected $fillable = ['name', 'domain', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'group_users',
            'group_id',
            'user_id'
        )
            ->withPivot('admin')
            ->using(GroupUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function environments()
    {
        return $this->hasMany(GroupEnvironment::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userInvitations()
    {
        return $this->hasMany(GroupUserInvitation::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(
            Session::class,
            'group_users',
            'group_id',
            'user_id',
            'id',
            'owner_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testCases()
    {
        return $this->belongsToMany(
            TestCase::class,
            'group_test_cases',
            'group_id',
            'test_case_id'
        );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users()
            ->whereKey($user->getKey())
            ->exists();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasAdminUser(User $user)
    {
        return $this->users()
            ->whereKey($user->getKey())
            ->wherePivot('admin', true)
            ->exists();
    }
}
