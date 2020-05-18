<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestCasePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTestCaseCreator() && in_array($ability, ['viewAny', 'view', 'create'])) {
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
     * @param  TestCase  $model
     * @return mixed
     */
    public function view(User $user, TestCase $model)
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
     * @param  TestCase  $model
     * @return mixed
     */
    public function update(User $user, TestCase $model)
    {

    }

    /**
     * @param  User  $user
     * @param  TestCase  $model
     * @return mixed
     */
    public function delete(User $user, TestCase $model)
    {

    }
}
