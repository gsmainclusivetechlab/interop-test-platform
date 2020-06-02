<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (!$model->isDirty($model->getUuidColumn())) {
                $model->generateUuid();
            }
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
