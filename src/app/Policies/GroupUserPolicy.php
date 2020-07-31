<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupUserPolicy
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
     * @param  GroupUser  $model
     * @return mixed
     */
    public function view(User $user, GroupUser $model)
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
     * @param  GroupUser  $model
     * @return mixed
     */
    public function update(User $user, GroupUser $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  GroupUser  $model
     * @return mixed
     */
    public function delete(User $user, GroupUser $model)
    {
        return $user->isAdmin() ||
            (!$model->admin && $model->group->hasAdminUser($user));
    }
}
