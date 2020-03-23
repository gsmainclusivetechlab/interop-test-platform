<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Eloquent
 */
class TestResponseExecution extends TestExecution
{
    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            $builder->whereHas('testResponseStep');
        });
    }
}
