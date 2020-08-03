<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\ApiSpec;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiSpecPolicy
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
     * @param  ApiSpec  $model
     * @return mixed
     */
    public function view(User $user, ApiSpec $model)
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
     * @param  ApiSpec  $model
     * @return mixed
     */
    public function update(User $user, ApiSpec $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  ApiSpec  $model
     * @return mixed
     */
    public function delete(User $user, ApiSpec $model)
    {
        return $user->isAdmin();
    }
}
