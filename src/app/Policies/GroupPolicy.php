<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * @param  Group  $model
     * @return mixed
     */
    public function view(User $user, Group $model)
    {
        return $user->canAdmin() || $model->hasUser($user);
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
     * @param  Group  $model
     * @return mixed
     */
    public function update(User $user, Group $model)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  Group  $model
     * @return mixed
     */
    public function delete(User $user, Group $model)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  Group  $model
     * @return mixed
     */
    public function invite(User $user, Group $model)
    {
        return $user->canAdmin() || $model->hasAdminUser($user);
    }
}
