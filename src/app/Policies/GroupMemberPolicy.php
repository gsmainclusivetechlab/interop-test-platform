<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupMemberPolicy
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
     * @param  GroupMember  $model
     * @return mixed
     */
    public function view(User $user, GroupMember $model)
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
     * @param  GroupMember  $model
     * @return mixed
     */
    public function update(User $user, GroupMember $model)
    {
        return $user->canAdmin();
    }

    /**
     * @param  User  $user
     * @param  GroupMember  $model
     * @return mixed
     */
    public function delete(User $user, GroupMember $model)
    {
        return $user->canAdmin() || (!$model->admin && $model->group->hasAdminMember($user));
    }
}
