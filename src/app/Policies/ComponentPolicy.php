<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Component;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComponentPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isTestCaseCreator();
    }

    /**
     * @param  User  $user
     * @param  Component  $model
     * @return mixed
     */
    public function view(User $user, Component $model)
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
     * @param  Component  $model
     * @return mixed
     */
    public function update(User $user, Component $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  Component  $model
     * @return mixed
     */
    public function delete(User $user, Component $model)
    {
        return $user->isAdmin();
    }
}
