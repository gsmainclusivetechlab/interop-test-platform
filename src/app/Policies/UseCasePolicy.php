<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\UseCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UseCasePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->canAdmin()) {
            return true;
        }
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

    }

    /**
     * @param  User  $user
     * @param  UseCase  $model
     * @return mixed
     */
    public function view(User $user, UseCase $model)
    {

    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * @param  User  $user
     * @param  UseCase  $model
     * @return mixed
     */
    public function update(User $user, UseCase $model)
    {

    }

    /**
     * @param  User  $user
     * @param  UseCase  $model
     * @return mixed
     */
    public function delete(User $user, UseCase $model)
    {

    }
}
