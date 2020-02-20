<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Eloquent
 */
class TestSessionCase extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'test_sessions_cases';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->suite_id = $model->case()->value('suite_id');
        });
    }
}
