<?php

namespace App\Enums;

use Illuminate\Support\Arr;

class UserRoleEnum extends Enum
{
    const USER = 'user';
    const ADMIN = 'admin';
    const SUPERADMIN = 'superadmin';

    /**
     * @return mixed
     */
    public function label()
    {
        return Arr::get($this->labels(), $this->value);
    }

    /**
     * @return array
     */
    public static function labels()
    {
        return [
            static::USER => __('User'),
            static::ADMIN => __('Admin'),
            static::SUPERADMIN => __('Superadmin'),
        ];
    }
}
