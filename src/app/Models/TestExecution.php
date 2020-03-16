<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestExecution extends Model
{
    const UPDATED_AT = null;

    const STATUS_PASSED = 'passed';
    const STATUS_FAILURE = 'failure';

    /**
     * @var string
     */
    protected $table = 'test_executions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'exception',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function passed(string $name)
    {
        $this->name = $name;
        $this->status = static::STATUS_PASSED;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('passed');
        return true;
    }

    /**
     * @param string $name
     * @param string|null $exception
     * @return bool
     */
    public function failure(string $name, string $exception = null)
    {
        $this->name = $name;
        $this->status = static::STATUS_FAILURE;
        $this->exception = $exception;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('failure');
        return true;
    }
}
