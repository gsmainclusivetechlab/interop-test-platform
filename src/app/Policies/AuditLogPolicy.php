<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditLogPolicy
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
     * @param  AuditLog  $model
     * @return mixed
     */
    public function view(User $user, AuditLog $model)
    {
        return $user->isAdmin() ||
            $model
                ->whereHas('owner', function ($query) use ($user) {
                    $query->whereKey($user->getKey());
                })
                ->exists();
    }
}
