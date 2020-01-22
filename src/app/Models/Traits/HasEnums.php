<?php

namespace App\Models\Traits;

trait HasEnums
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($value && $this->isEnumAttribute($key)) {
            return $this->asEnum($key, $value);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if ($value && $this->isEnumAttribute($key)) {
            $value = $this->fromEnum($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    protected function asEnum($key, $value)
    {
        $class = $this->enums[$key];
        return new $class($value);
    }

    protected function fromEnum($key, $value)
    {
        return empty($value) ? $value : $this->asEnum($key, $value)->getValue();
    }

    protected function isEnumAttribute(string $key)
    {
        return isset($this->enums[$key]);
    }
}
