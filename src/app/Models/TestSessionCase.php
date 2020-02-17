<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Eloquent
 */
class TestSessionCase extends Pivot
{
    use HasUuid;

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

        static::saving(function (Model $model) {
            $model->operation_id = $model->case()->value('operation_id');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo(TestOperation::class, 'operation_id');
    }
}
