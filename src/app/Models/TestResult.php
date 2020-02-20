<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin Eloquent
 */
class TestResult extends Model
{
    use HasPosition;

    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_PASSED = 'passed';
    const STATUS_ERROR = 'error';
    const STATUS_FAILURE = 'failure';

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [
        'step_id',
        'time',
        'status',
        'request',
        'response',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_INCOMPLETE,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function run()
    {
        return $this->belongsTo(TestRun::class, 'run_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(TestStep::class, 'step_id');
    }

    /**
     * @return array
     */
    public static function getStatusTypes()
    {
        return [
            static::STATUS_INCOMPLETE => 'secondary',
            static::STATUS_PASSED => 'success',
            static::STATUS_ERROR => 'danger',
            static::STATUS_FAILURE => 'danger',
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusTypeAttribute()
    {
        return Arr::get(TestResult::getStatusTypes(), $this->status);
    }

    /**
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            static::STATUS_INCOMPLETE => __('Incomplete'),
            static::STATUS_PASSED => __('Passed'),
            static::STATUS_ERROR => __('Error'),
            static::STATUS_FAILURE => __('Failure'),
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusLabelAttribute()
    {
        return Arr::get(TestResult::getStatusLabels(), $this->status);
    }
}
