<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestCasePolicy
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
     * @return mixed
     */
    public function viewAnyPrivate(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  TestCase  $model
     * @return mixed
     */
    public function view(User $user, TestCase $model)
    {
        return $user->isAdmin() || $user->is($model->owner);
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isTestCaseCreator();
    }

    /**
     * @param  User  $user
     * @param  TestCase  $model
     * @return mixed
     */
    public function update(User $user, TestCase $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  TestCase  $model
     * @return mixed
     */
    public function delete(User $user, TestCase $model)
    {
        return $user->isAdmin() ||
            ($user->is($model->owner) && !$model->public);
    }
}
