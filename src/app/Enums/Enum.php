<?php

namespace App\Enums;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Enum implements Enumerable
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public static function values()
    {
        try {
            return array_values((new \ReflectionClass(static::class))->getConstants());
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    /**
     * @return mixed
     */
    public function label()
    {
        return Arr::get($this->labels(), $this->value, $this->value);
    }

    /**
     * @return array
     */
    public static function labels()
    {
        $labels = [];
        $values = static::values();

        foreach ($values as $value) {
            $labels[$value] = Str::title($value);
        }

        return $labels;
    }

    /**
     * @param $value
     * @return bool
     */
    public function is($value)
    {
        return $this->value === $value;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function in(array $values)
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->value;
    }
}
