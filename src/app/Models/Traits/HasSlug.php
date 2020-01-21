<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * @return void
     */
    protected static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            $model->generateSlug();
        });
    }

    /**
     * @return void
     */
    protected function generateSlug()
    {
        if ($this->isNewSlugNeeded()) {
            $slug = '';

            if (!empty($this->getSlugSourceColumn())) {
                $slugParts = [];

                foreach ((array) $this->getSlugSourceColumn() as $attribute) {
                    $slugParts[] = $this->getAttribute($attribute);
                }

                $slug = implode($this->getSlugSeparator(), $slugParts);
            }

            $this->setAttribute($this->getSlugColumn(), Str::slug($this->makeUniqueSlug($slug), $this->getSlugSeparator()));
        }
    }

    /**
     * @param $slug
     * @return string
     */
    protected function makeUnique($slug)
    {
        $uniqueSlug = $slug;
        $iteration = 0;

        while (!$this->validateSlug($uniqueSlug)) {
            $iteration++;
            $uniqueSlug = implode($this->getSlugSeparator(), [$slug, ($iteration + 1)]);
        }

        return $uniqueSlug;
    }

    /**
     * @param $slug
     * @return bool
     */
    protected function validateSlug($slug)
    {
        return $this->query()
            ->where($this->getSlugColumn(), $slug)
            ->exists();
    }


    /**
     * @return bool
     */
    protected function isNewSlugNeeded()
    {
        if (empty($this->getAttribute($this->getSlugColumn()))) {
            return true;
        }

        if (empty($this->getSlugSourceColumn())) {
            return true;
        }

        foreach ((array) $this->getSlugSourceColumn() as $attribute) {
            if ($this->isDirty($attribute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSlugColumn()
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function getSlugSeparator()
    {
        return '-';
    }

    /**
     * @return string|array
     */
    public function getSlugSourceColumn()
    {
        return [];
    }
}
