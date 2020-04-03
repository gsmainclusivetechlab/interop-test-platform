<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
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
    protected $observables = [
        'complete',
    ];

    public function getChartHourAttribute()
	{
		$format = 'H\h';

		if (!$this->created_at->isToday()) {
			$format = 'j M ' . $format;
		}

		return $this->created_at->format($format);
	}

    public function getChartDayAttribute()
	{
		$format = 'j M';

		if (!$this->created_at->isCurrentUnit('month')) {
			$format .= ' Y';
		}

		return $this->created_at->format($format);
	}

    public function getChartMonthAttribute()
	{
		$format = 'M';

		if (!$this->created_at->isCurrentUnit('year')) {
			$format .= ' Y';
		}

		return $this->created_at->format('M');
	}

    public function getChartYearAttribute()
	{
		return $this->created_at->format('Y');
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

    public function scopeFailures($query)
	{
		return $query->whereFailures('>', 0);
	}

    /**
     * @return string
     */
    public function getTraceIdAttribute()
    {
        return str_replace('-', '', $this->uuid);
    }

    /**
     * @return mixed
     */
    public function getDurationAttribute()
    {
        return $this->testResults()->sum('duration');
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->testSteps()->count() === $this->testResults()->successful()->count();
    }

    /**
     * @return bool
     */
    public function complete()
    {
        $this->successful = $this->wasSuccessful();
        $this->completed_at = now();

        if (!$this->save()) {
            return false;
        }

        $this->fireModelEvent('complete');
        return true;
    }
}
