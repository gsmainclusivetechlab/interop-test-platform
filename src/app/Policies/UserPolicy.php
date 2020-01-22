<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param  User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return ($user->isAdmin() && !$model->isAdmin()) || ($user->isSuperAdmin() && !$user->is($model));
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        return ($user->isAdmin() && !$model->isAdmin()) || ($user->isSuperAdmin() && !$user->is($model));
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return ($user->isAdmin() && !$model->isAdmin()) || ($user->isSuperAdmin() && !$user->is($model));
    }

    /**
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function promoteAdmin(User $user, User $model)
    {
        return ($user->isSuperAdmin() && !$user->is($model) && !$model->isAdmin());
    }

    /**
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function relegateAdmin(User $user, User $model)
    {
        return ($user->isSuperAdmin() && !$user->is($model) && $model->isAdmin());
    }
}
