<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \Eloquent
 *
 * @property int $duration
 * @property Carbon $completed_at
 *
 * @property Session $session
 * @property TestResult[]|Collection $testResults
 *
 * @property-read bool $successful
 */
class TestRun extends Model
{
    use HasUuid;

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_runs';

    /**
     * @var array
     */
    protected $fillable = ['test_case_id', 'session_id'];

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
        'total' => 0,
        'passed' => 0,
        'failures' => 0,
        'duration' => 0,
    ];

    /**
     * @var array
     */
    protected $observables = ['complete'];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('session_test_case', function (
            Builder $builder
        ) {
            $builder->whereExists(function ($query) {
                $query
                    ->select(DB::raw(1))
                    ->from('session_test_cases')
                    ->where('session_test_cases.deleted_at', null)
                    ->whereColumn(
                        'session_test_cases.session_id',
                        'test_runs.session_id'
                    )
                    ->whereColumn(
                        'session_test_cases.test_case_id',
                        'test_runs.test_case_id'
                    );
            });
        });
    }

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
        return $this->hasManyThrough(
            TestStep::class,
            TestCase::class,
            'id',
            'test_case_id',
            'test_case_id',
            'id'
        );
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
     * @param $query
     * @param $traceId
     * @return mixed
     */
    public function scopeWhereTraceId($query, $traceId)
    {
        return $query->whereRaw('REPLACE(uuid, "-", "") = ?', $traceId);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->whereRaw('total = passed');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnsuccessful($query)
    {
        return $query->whereRaw('total != passed');
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncompleted($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * @return string
     */
    public function getTraceIdAttribute()
    {
        return str_replace('-', '', $this->uuid);
    }

    /**
     * @return bool
     */
    public function getSuccessfulAttribute()
    {
        return $this->total === $this->passed;
    }

    /**
     * @return $this|bool
     */
    public function complete()
    {
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('complete');
        return $this;
    }

    /**
     * @param TestStep $testStep
     * @return TestResult
     */
    public function getTestResultOrCreate($testStep): TestResult
    {
        $testResult = $this->testResults()
            ->where(['test_step_id' => $testStep->id])
            ->first();
        $testResult = $testResult ?? $this->testResults()
            ->create([
                'test_step_id' => $testStep->id,
                'iteration' => 0,
                'completed' => false,
            ]);

        return $testResult;
    }
}
