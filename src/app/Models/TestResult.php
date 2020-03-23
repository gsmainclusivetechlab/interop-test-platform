<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestResult extends Model
{
    const UPDATED_AT = null;

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
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
        'completed_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $observables = [
        'complete',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testScripts()
    {
        return $this->hasManyThrough(TestScript::class, TestStep::class, 'id', 'test_step_id', 'test_step_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testExecutions()
    {
        return $this->hasMany(TestExecution::class, 'test_result_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRequestExecutions()
    {
        return $this->testExecutions()->whereHas('testScript', function (Builder $query) {
            $query->where('type', TestScript::TYPE_REQUEST);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testResponseExecutions()
    {
        return $this->testExecutions()->whereHas('testScript', function (Builder $query) {
            $query->where('type', TestScript::TYPE_RESPONSE);
        });
    }

    /**
     * @return bool
     */
    public function isLast()
    {
        return $this->testStep->isLastPosition();
    }

    /**
     * @return bool
     */
    public function complete()
    {
        $this->successful = !$this->testExecutions()
            ->where('status', TestExecution::STATUS_FAIL)
            ->orWhere('status', TestExecution::STATUS_ERROR)
            ->exists();
        $this->time = floor((microtime(true) - LARAVEL_START) * 1000);
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('complete');
        return true;
    }
}
