<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Environment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnvironmentPolicy
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
     * @param  Environment  $model
     * @return mixed
     */
    public function view(User $user, Environment $model)
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
     * @param  Environment  $model
     * @return mixed
     */
    public function update(User $user, Environment $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  Environment  $model
     * @return mixed
     */
    public function delete(User $user, Environment $model)
    {
        return ($user->isAdmin());
    }
}
