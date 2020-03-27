<?php

namespace App\Enums;

interface Enumerable
{
    /**
     * @return mixed
     */
    public function value();

    /**
     * @return array
     */
    public static function values();
}
