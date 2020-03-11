<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestRun extends Model
{
    use HasUuid;

    const UPDATED_AT = null;

    const STATUS_EXECUTING = 'executing';
    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';
    const STATUS_TIMEOUT = 'timeout';

    /**
     * @var string
     */
    protected $table = 'test_runs';

    /**
     * @var array
     */
    protected $fillable = [
        'session_id',
        'test_case_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_EXECUTING,
    ];

    /**
     * @var array
     */
    protected $with = [
        'session',
        'testCase',
    ];

//    /**
//     * @var array
//     */
//    protected $withCount = [
//        'steps',
//        'results',
//        'passResults',
//        'failResults',
//        'errorResults',
//    ];

    /**
     * @var array
     */
    protected $observables = [
        'pass',
        'fail',
        'timeout',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testSteps()
    {
        return $this->hasManyThrough(TestStep::class, TestCase::class, 'id', 'test_case_id', 'test_case_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'test_run_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passTestResults()
    {
        return $this->testResults()->pass();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function failResults()
    {
        return $this->testResults()->fail();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errorResults()
    {
        return $this->testResults()->error();
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
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * @return int
     */
    public function getDurationAttribute()
    {
        return $this->results()->sum('time');
    }

    /**
     * @return string
     */
    public function getTraceIdAttribute()
    {
        return str_replace('-', '', $this->uuid);
    }

    /**
     * @return array
     */
    public static function getStatusTypes()
    {
        return [
            static::STATUS_EXECUTING => 'secondary',
            static::STATUS_PASS => 'success',
            static::STATUS_FAIL => 'danger',
            static::STATUS_TIMEOUT => 'secondary',
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
            static::STATUS_EXECUTING => __('Executing'),
            static::STATUS_PASS => __('Pass'),
            static::STATUS_FAIL => __('Fail'),
            static::STATUS_TIMEOUT => __('Timeout'),
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
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed_at != null;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function pass(array $options = [])
    {
        $this->status = static::STATUS_PASS;
        $this->completed_at = now();

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('pass');
        return true;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function fail(array $options = [])
    {
        $this->status = static::STATUS_FAIL;
        $this->completed_at = now();

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('fail');
        return true;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function timeout(array $options = [])
    {
        $this->status = static::STATUS_TIMEOUT;
        $this->completed_at = now();

        if (!$this->save($options)) {
            return false;
        }

        $this->fireModelEvent('timeout');
        return true;
    }
}
