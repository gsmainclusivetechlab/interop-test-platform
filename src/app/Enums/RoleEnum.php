<?php declare(strict_types=1);

namespace App\Enums;

class RoleEnum extends Enum
{
    const USER = 'user';
    const ADMIN = 'admin';
    const SUPER_ADMIN = 'super-admin';
}
