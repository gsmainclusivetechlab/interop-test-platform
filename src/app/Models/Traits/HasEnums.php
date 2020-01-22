<?php

namespace App\Models\Traits;

trait HasEnums
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->isEnumAttribute($key)) {
            return $this->getEnumAttribute($key, $value);
        } else {
            return $value;
        }
    }

    protected function getEnumAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        return $this->asEnum($key, $value);
    }

    protected function asEnum($key, $value)
    {
        $class = $this->enums[$key];
        return new $class($value);
    }

    protected function isEnumAttribute(string $key)
    {
        return isset($this->enums[$key]);
    }

    protected function getEnumClass($key)
    {
        return $this->enums[$key];
    }
}
