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

    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';
    const STATUS_ERROR = 'error';

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
        'pass',
        'fail',
        'error',
    ];

    /**
     * @return array
     */
    public static function getStatusTypes()
    {
        return [
            static::STATUS_PASS => 'success',
            static::STATUS_FAIL => 'danger',
            static::STATUS_ERROR => 'danger',
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
    public function pass(string $name)
    {
        $this->name = $name;
        $this->status = static::STATUS_PASS;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('pass');
        return true;
    }

    /**
     * @param string $name
     * @param string|null $exception
     * @return bool
     */
    public function fail(string $name, string $exception = null)
    {
        $this->name = $name;
        $this->status = static::STATUS_FAIL;
        $this->exception = $exception;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('fail');
        return true;
    }

    /**
     * @param string $name
     * @param string|null $exception
     * @return bool
     */
    public function error(string $name, string $exception = null)
    {
        $this->name = $name;
        $this->status = static::STATUS_ERROR;
        $this->exception = $exception;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('error');
        return true;
    }
}
