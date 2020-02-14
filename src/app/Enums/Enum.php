<?php declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use UnexpectedValueException;

abstract class Enum
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param $value
     * @throws \ReflectionException
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            $value = $value->getValue();
        }

        if (!$this->isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum " . \get_called_class());
        }

        $this->value = $value;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function values()
    {
        return array_values(static::toArray());
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function is($value)
    {
        if ($value instanceof static) {
            return $this->getValue() === $value->getValue();
        } else {
            return $this->getValue() === $value;
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function toArray()
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->getConstants();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
