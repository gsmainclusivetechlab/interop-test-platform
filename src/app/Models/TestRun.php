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

    const STATUS_PROCESSING = 'processing';
    const STATUS_PASSED = 'passed';
    const STATUS_FAILURE = 'failure';

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
    protected $attributes = [
        'status' => self::STATUS_PROCESSING,
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
//    protected $with = [
//        'session',
//        'testCase',
//    ];

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
        'passed',
        'failure',
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
    public function passedTestResults()
    {
        return $this->testResults()->passed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function failureTestResults()
    {
        return $this->testResults()->failure();
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
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
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            static::STATUS_PROCESSING => __('Processing'),
            static::STATUS_PASSED => __('Pass'),
            static::STATUS_FAILURE => __('Fail'),
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
