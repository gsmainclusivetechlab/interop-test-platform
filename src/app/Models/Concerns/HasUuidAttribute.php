<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasUuidAttribute
{
    /**
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            $model->generateUuid();
        });
    }

    /**
     * @return void
     */
    protected function generateUuid()
    {
        $this->setAttribute($this->getUuidColumn(), Str::uuid());
    }

    /**
     * @return string
     */
    public function getUuidColumn()
    {
        return 'uuid';
    }
}
