<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestSessionPolicy
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
     * @param  TestSession  $model
     * @return mixed
     */
    public function view(User $user, TestSession $model)
    {
        return $user->isAdmin() || $model->owner_id == $user->id;
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
     * @param  TestSession  $model
     * @return mixed
     */
    public function update(User $user, TestSession $model)
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  TestSession  $model
     * @return mixed
     */
    public function delete(User $user, TestSession $model)
    {
        return ($user->isAdmin());
    }
}
