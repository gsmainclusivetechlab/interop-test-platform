<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRun extends Model
{
    use HasUuidAttribute;

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_runs';

    /**
     * @var array
     */
    protected $fillable = [
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
        'total' => 0,
        'passed' => 0,
        'failures' => 0,
        'duration' => 0,
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
     * @return bool
     */
    public function getSuccessfulAttribute()
    {
        return $this->total === $this->passed;
    }

    /**
     * @return bool
     */
    public function complete()
    {
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('complete');
        return true;
    }
}
