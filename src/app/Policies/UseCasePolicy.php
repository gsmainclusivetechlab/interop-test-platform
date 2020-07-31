<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\UseCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UseCasePolicy
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
     * @param  UseCase  $model
     * @return mixed
     */
    public function view(User $user, UseCase $model)
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
     * @param  UseCase  $model
     * @return mixed
     */
    public function update(User $user, UseCase $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  UseCase  $model
     * @return mixed
     */
    public function delete(User $user, UseCase $model)
    {
        return $user->isAdmin();
    }
}
