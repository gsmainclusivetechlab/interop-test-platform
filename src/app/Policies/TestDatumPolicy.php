<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\TestDatum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestDatumPolicy
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
     * @param  TestDatum  $model
     * @return mixed
     */
    public function view(User $user, TestDatum $model)
    {
        return $model->session->owner->is($user);
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
     * @param  TestDatum  $model
     * @return mixed
     */
    public function update(User $user, TestDatum $model)
    {
        return $model->session->owner->is($user);
    }

    /**
     * @param  User  $user
     * @param  TestDatum  $model
     * @return mixed
     */
    public function delete(User $user, TestDatum $model)
    {
        return $model->session->owner->is($user);
    }

    /**
     * @param User $user
     * @param TestDatum $model
     * @return mixed
     */
    public function run(User $user, TestDatum $model)
    {
        return $model->session->owner->is($user);
    }
}
