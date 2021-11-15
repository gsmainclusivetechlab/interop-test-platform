<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @param  User  $user
     * @return mixed
     */
    public function toggleActive(User $user)
    {
        return $user->isAdmin() && env('FEATURE_FAQ');
    }

    /**
     * @return mixed
     */
    public function viewContent()
    {
        return Faq::where(['active' => true])->exists() && env('FEATURE_FAQ');
    }
}
