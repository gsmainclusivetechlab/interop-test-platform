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
    const UPDATED_AT = null;

    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';
    const STATUS_ERROR = 'error';

    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [
        'step_id',
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
        'pass',
        'fail',
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePass($query)
    {
        return $query->where('status', static::STATUS_PASS);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFail($query)
    {
        return $query->where('status', static::STATUS_FAIL);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeError($query)
    {
        return $query->where('status', static::STATUS_ERROR);
    }

    /**
     * @return array
     */
    public static function getStatusTypes()
    {
        return [
            static::STATUS_PASS => 'success',
            static::STATUS_FAIL => 'danger',
            static::STATUS_ERROR => 'warning',
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
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            static::STATUS_PASS => __('Pass'),
            static::STATUS_FAIL => __('Fail'),
            static::STATUS_ERROR => __('Error'),
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusLabelAttribute()
    {
        return Arr::get(static::getStatusLabels(), $this->status);
    }

    /**
     * @param string|null $message
     * @param array $options
     * @return bool
     */
    public function pass(string $message = null, array $options = [])
    {
        $this->status = static::STATUS_PASS;
        $this->status_message = $message;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('pass');
        return true;
    }

    /**
     * @param string|null $message
     * @param array $options
     * @return bool
     */
    public function fail(string $message = null, array $options = [])
    {
        $this->status = static::STATUS_FAIL;
        $this->status_message = $message;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('fail');
        return true;
    }

    /**
     * @param string|null $message
     * @param array $options
     * @return bool
     */
    public function error(string $message = null, array $options = [])
    {
        $this->status = static::STATUS_ERROR;
        $this->status_message = $message;
        $this->time = floor(Timer::stop() * 1000);

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('error');
        return true;
    }
}
