<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Str;

trait HasSlug
{
    protected $slugField = 'slug';
    protected $slugSource = 'name';

    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });
    }

    protected function generateSlug(): void
    {
        if ($this->{$this->slugField}) {
            return;
        }

        $i = 1;
        $slug = $originalSlug = Str::slug($this->{$this->slugSource});

        while ($this->otherRecordExistsWithSlug($slug)) {
            $slug = $originalSlug . '-' . $i++;
        }

        $this->{$this->slugField} = $slug;
    }

    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        return static::where($this->slugField, $slug)
            ->withoutGlobalScopes()
            ->exists();
    }
}
