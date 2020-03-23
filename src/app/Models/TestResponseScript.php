<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Eloquent
 */
class TestResponseScript extends TestScript
{
    /**
     * @var array
     */
    protected $attributes = [
        'type' => self::TYPE_RESPONSE,
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('type', static::TYPE_RESPONSE);
        });
    }
}
