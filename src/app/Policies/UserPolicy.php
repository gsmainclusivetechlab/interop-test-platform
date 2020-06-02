<?php declare(strict_types=1);

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
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return ($user->canAdmin() && !$model->canAdmin()) ||
            ($user->canSuperAdmin() && !$user->is($model));
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        return ($user->canAdmin() && !$model->canAdmin()) ||
            ($user->canSuperAdmin() && !$user->is($model));
    }

    /**
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function verify(User $user, User $model)
    {
        return $user->canAdmin() &&
            !$user->is($model) &&
            !$model->hasVerifiedEmail();
    }

    /**
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function promoteRole(User $user, User $model)
    {
        return $user->canSuperAdmin() && !$user->is($model);
    }
}
