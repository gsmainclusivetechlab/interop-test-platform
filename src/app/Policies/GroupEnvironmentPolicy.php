<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\GroupEnvironment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupEnvironmentPolicy
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
     * @param  GroupEnvironment  $model
     * @return mixed
     */
    public function view(User $user, GroupEnvironment $model)
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
     * @param  GroupEnvironment  $model
     * @return mixed
     */
    public function update(User $user, GroupEnvironment $model)
    {
        return $user->isAdmin() || $model->group->hasAdminUser($user);
    }

    /**
     * @param  User  $user
     * @param  GroupEnvironment  $model
     * @return mixed
     */
    public function delete(User $user, GroupEnvironment $model)
    {
        return $user->isAdmin() || $model->group->hasAdminUser($user);
    }
}
