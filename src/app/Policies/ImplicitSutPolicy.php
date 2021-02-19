<?php

namespace App\Policies;

use App\Models\{ImplicitSut, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class ImplicitSutPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
