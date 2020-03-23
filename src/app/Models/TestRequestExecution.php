<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Eloquent
 */
class TestRequestExecution extends TestExecution
{
    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            $builder->whereHas('testRequestStep');
        });
    }
}
