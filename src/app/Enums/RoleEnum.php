<?php

namespace App\Enums;

class RoleEnum extends Enum
{
    const USER = 'user';
    const ADMIN = 'admin';
    const SUPER_ADMIN = 'super-admin';

    public function isAdmin()
    {
        return in_array($this->getValue(), [static::ADMIN, static::SUPER_ADMIN]);
    }

    public function isSuperAdmin()
    {
        return in_array($this->getValue(), [static::SUPER_ADMIN]);
    }
}
