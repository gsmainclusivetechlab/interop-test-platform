<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestExecution extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_executions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'actual',
        'expected',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'actual' => 'array',
        'expected' => 'array',
    ];

    /**
     * @var array
     */
    protected $observables = [
        'successful',
        'unsuccessful',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('successful', true);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnsuccessful($query)
    {
        return $query->where('successful', false);
    }

    /**
     * @return $this|bool
     */
    public function successful()
    {
        $this->successful = true;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('successful');
        return $this;
    }

    /**
     * @param string $exception
     * @return $this|bool
     */
    public function unsuccessful(string $exception)
    {
        $this->successful = false;
        $this->exception = $exception;

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('unsuccessful');
        return $this;
    }
}
