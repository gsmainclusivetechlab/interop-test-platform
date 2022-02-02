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
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function view(User $user, Session $model)
    {
        return $user->isAdmin() ||
            $model->owner->is($user) ||
            $user
                ->groups()
                ->whereHas('users', function ($query) use ($model) {
                    $query->whereKey($model->owner->getKey());
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
        return ($user->isAdmin() || $model->owner->is($user)) &&
            $model->isAvailableToUpdate();
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function delete(User $user, Session $model)
    {
        return $user->isAdmin() || $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  Session  $model
     * @return mixed
     */
    public function owner(User $user, Session $model)
    {
        return $model->owner->is($user);
    }
}
