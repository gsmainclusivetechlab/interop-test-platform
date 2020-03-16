<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestResult extends Model
{
    const UPDATED_AT = null;

    const STATUS_PROCESSING = 'processing';
    const STATUS_PASSED = 'passed';
    const STATUS_FAILURE = 'failure';

    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [
        'test_step_id',
        'request',
        'response',
        'exception',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PROCESSING,
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
    ];

    /**
     * @var array
     */
    protected $observables = [
        'passed',
        'failure',
    ];

    /**
     * @return array
     */
    public static function getStatusTypes()
    {
        return [
            static::STATUS_PROCESSING => 'secondary',
            static::STATUS_PASSED => 'success',
            static::STATUS_FAILURE => 'danger',
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusTypeAttribute()
    {
        return Arr::get(static::getStatusTypes(), $this->status);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testRun()
    {
        return $this->belongsTo(TestRun::class, 'test_run_id');
    }

    public function testStep()
    {
        return $this->belongsTo(TestStep::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testExecutions()
    {
        return $this->hasMany(TestExecution::class, 'test_result_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePassed($query)
    {
        return $query->where('status', static::STATUS_PASSED);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailure($query)
    {
        return $query->where('status', static::STATUS_FAILURE);
    }

    /**
     * @return bool
     */
    public function passed()
    {
        $this->status = static::STATUS_PASSED;
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('passed');
        return true;
    }

    /**
     * @param string|null $exception
     * @return bool
     */
    public function failure(string $exception = null)
    {
        $this->status = static::STATUS_FAILURE;
        $this->exception = $exception;
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('failure');
        return true;
    }
}
