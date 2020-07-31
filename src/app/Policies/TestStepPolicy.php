<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestStep;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestStepPolicy
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
     * @param  TestStep  $model
     * @return mixed
     */
    public function view(User $user, TestStep $model)
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
     * @param  TestStep  $model
     * @return mixed
     */
    public function update(User $user, TestStep $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  TestStep  $model
     * @return mixed
     */
    public function delete(User $user, TestStep $model)
    {
        return $user->isAdmin();
    }
}
