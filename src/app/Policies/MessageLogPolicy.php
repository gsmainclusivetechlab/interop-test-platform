<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\MessageLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageLogPolicy
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
     * @param  MessageLog  $model
     * @return mixed
     */
    public function view(User $user, MessageLog $model)
    {
        return $user->isAdmin() ||
            $model
                ->session()
                ->whereHas('owner', function ($query) use ($user) {
                    $query->whereKey($user->getKey());
                })
                ->exists();
    }
}
