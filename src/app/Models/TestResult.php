<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use SebastianBergmann\Timer\Timer;

/**
 * @mixin Eloquent
 */
class TestResult extends Model
{
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

    /**
     * @var array
     */
    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];

    /**
     * @var array
     */
    protected $observables = [
        'passed',
        'failed',
        'error',
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

    /**
     * @param string|null $message
     * @return bool
     */
    public function passed(string $message = null)
    {
        $this->status_message = $message;
        $this->status = static::STATUS_PASSED;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('passed');
        return true;
    }

    /**
     * @param string|null $message
     * @return bool
     */
    public function failed(string $message = null)
    {
        $this->status_message = $message;
        $this->status = static::STATUS_FAILURE;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('failed');
        return true;
    }

    /**
     * @param string|null $message
     * @return bool
     */
    public function error(string $message = null)
    {
        $this->status_message = $message;
        $this->status = static::STATUS_ERROR;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('error');
        return true;
    }
}
