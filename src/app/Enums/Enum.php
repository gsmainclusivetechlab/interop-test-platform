<?php

namespace App\Enums;

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
     * @throws \ReflectionException
     */
    public static function values()
    {
        return array_values((new \ReflectionClass(static::class))->getConstants());
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
