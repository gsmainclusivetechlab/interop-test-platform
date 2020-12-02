<?php

namespace App\Enums;

/**
 * Class HttpMethod
 * @package App\Enums
 */
class HttpMethod
{
    /**
     * @return array
     */
    public static function list()
    {
        return [
            'GET' => 'GET',
            'POST' => 'POST',
            'PUT' => 'PUT',
            'PATCH' => 'PATCH',
            'DELETE' => 'DELETE'
        ];
    }
}
