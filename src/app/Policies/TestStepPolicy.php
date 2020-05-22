<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestStep;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestStepPolicy
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
     * @param  TestStep  $model
     * @return mixed
     */
    public function view(User $user, TestStep $model)
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
     * @param  TestStep  $model
     * @return mixed
     */
    public function update(User $user, TestStep $model)
    {

    }

    /**
     * @param  User  $user
     * @param  TestStep  $model
     * @return mixed
     */
    public function delete(User $user, TestStep $model)
    {

    }
}
