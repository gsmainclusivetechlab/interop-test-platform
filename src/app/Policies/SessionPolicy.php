<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function view(User $user, Session $model)
    {
        return $user->canAdmin() ||
            $model->owner->is($user) ||
            $user->groups()
                ->whereHas('members', function ($query) use ($model) {
                    $query->whereKey($model->owner);
                })
                ->exists();
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function update(User $user, Session $model)
    {
        return $user->canAdmin() || $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function delete(User $user, Session $model)
    {
        return $user->canAdmin() || $model->owner->is($user);
    }
}
