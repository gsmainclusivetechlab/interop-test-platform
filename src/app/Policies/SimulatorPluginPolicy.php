<?php

namespace App\Policies;

use App\Models\SimulatorPlugin;
use App\Models\User;

class SimulatorPluginPolicy
{
    public function view(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SimulatorPlugin $model): bool
    {
        return $user->isAdmin() || $model->group->hasAdminUser($user);
    }

    public function delete(User $user, SimulatorPlugin $model): bool
    {
        return $user->isAdmin() || $model->group->hasAdminUser($user);
    }

    public function download(User $user, SimulatorPlugin $model): bool
    {
        return $user->isAdmin() || $model->group->hasAdminUser($user);
    }
}
