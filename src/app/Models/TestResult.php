<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use App\Http\Client\Request;
use App\Http\Client\Response;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @mixin Eloquent
 *
 * @property int $iteration
 *
 * @property Carbon $completed_at
 * @property TestStep $testStep
 * @property TestRun $testRun
 * @property Session $session
 * @property Request $request
 * @property Response $response
 */
class TestResult extends Model
{
    const UPDATED_AT = null;

    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';

    /** @var float */
    public $jobStart;

    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [
        'test_step_id',
        'iteration',
        'repeat',
        'request',
        'response',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'repeat' => 'boolean',
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_INCOMPLETE,
        'duration' => 0,
    ];

    /**
     * @var array
     */
    protected $observables = ['pass', 'fail'];

    /**
     * @return BelongsTo
     */
    public function testRun()
    {
        return $this->belongsTo(TestRun::class, 'test_run_id');
    }

    /**
     * @return BelongsTo
     */
    public function testStep()
    {
        return $this->belongsTo(TestStep::class, 'test_step_id');
    }

    /**
     * @return HasMany
     */
    public function testExecutions()
    {
        return $this->hasMany(TestExecution::class, 'test_result_id');
    }

    /**
     * @return HasOneThrough
     */
    public function session()
    {
        return $this->hasOneThrough(
            Session::class,
            TestRun::class,
            'id',
            'id',
            'test_run_id',
            'session_id'
        );
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where(['repeat' => false]);
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeIncompleted($query)
    {
        return $query->where(['repeat' => true]);
    }

    /**
     * @return bool
     */
    public function getSuccessfulAttribute()
    {
        return $this->status === static::STATUS_PASS;
    }

    /**
     * @return $this|bool
     */
    public function pass()
    {
        $this->status = static::STATUS_PASS;
        $this->duration = $this->getDuration();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('pass');
        return $this;
    }

    /**
     * @param string|null $exception
     * @return $this|bool
     */
    public function fail(string $exception = null)
    {
        $this->status = static::STATUS_FAIL;
        $this->exception = $exception;
        $this->duration = $this->getDuration();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('fail');
        return $this;
    }

    /**
     * @return int
     */
    protected function getDuration(): int
    {
        $startTime = $this->jobStart ?? LARAVEL_START;

        return (int) floor((microtime(true) - $startTime) * 1000);
    }
}
