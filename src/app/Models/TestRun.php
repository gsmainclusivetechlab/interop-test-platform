<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use SebastianBergmann\Timer\Timer;

/**
 * @mixin Eloquent
 */
class TestRun extends Model
{
    use HasUuid;

    const UPDATED_AT = null;

    const STATUS_EXECUTING = 'executing';
    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';

    /**
     * @var string
     */
    protected $table = 'test_runs';

    /**
     * @var array
     */
    protected $fillable = [
        'case_id',
        'session_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'completed_at' => 'date',
        'status' => self::STATUS_EXECUTING,
    ];

    /**
     * @var array
     */
    protected $observables = [
        'pass',
        'fail',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function steps()
    {
        return $this->hasManyThrough(TestStep::class, TestCase::class, 'id', 'case_id', 'case_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(TestResult::class, 'run_id');
    }

    /**
     * @return int
     */
    public function getDurationAttribute()
    {
        return floor($this->results()->sum('time') * 1000);
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
}
