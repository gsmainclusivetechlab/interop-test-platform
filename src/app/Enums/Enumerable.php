<?php

namespace App\Enums;

interface Enumerable
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return array
     */
    public static function getValues();

    /**
     * @param mixed $value
     * @return bool
     */
    public function is($value);

    /**
     * @param $value
     * @return bool
     */
    public static function isValid($value);
}
