<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestSessionPolicy
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
     * @param  TestSession  $model
     * @return mixed
     */
    public function view(User $user, TestSession $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * @param  User  $user
     * @param  TestSession  $model
     * @return mixed
     */
    public function update(User $user, TestSession $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  TestSession  $model
     * @return mixed
     */
    public function delete(User $user, TestSession $model)
    {
        return $model->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  TestSession  $model
     * @return mixed
     */
    public function restore(User $user, TestSession $model)
    {
        return $model->owner->is($user);
    }
}
