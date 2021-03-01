<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $name
 *
 * @mixin Eloquent
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
    protected $fillable = [
        'name',
        'domain',
        'description',
        'default_session_id',
    ];

    /**
     * @return BelongsToMany
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
     * @return HasMany
     */
    public function environments()
    {
        return $this->hasMany(GroupEnvironment::class, 'group_id');
    }

    /**
     * @return HasMany
     */
    public function userInvitations()
    {
        return $this->hasMany(GroupUserInvitation::class, 'group_id');
    }

    /**
     * @return BelongsToMany
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
     * @return null | Session
     */
    public function getDefaultSessionAttribute()
    {
        return $this->sessions()
            ->whereKey($this->default_session_id)
            ->first();
    }

    /**
     * @return BelongsToMany
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

    public function certificates(): MorphMany
    {
        return $this->morphMany(Certificate::class, 'certificable');
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
     * Regex validation rule for email by domain
     *
     * @return string
     */
    public function getEmailRegexAttribute()
    {
        $matchTo = str_replace(
            '.',
            '[.]',
            str_replace(', ', '|', $this->domain)
        );
        $matchTo = $matchTo ?? '*';

        return 'regex:/(' . $matchTo . ')$/';
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
