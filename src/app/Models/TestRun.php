<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin Eloquent
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
        'case_id',
        'session_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => TestResult::STATUS_INCOMPLETE,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
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
     * @return mixed
     */
    public function getStatusTypeAttribute()
    {
        return Arr::get(TestResult::getStatusTypes(), $this->status);
    }

    /**
     * @return mixed
     */
    public function getStatusLabelAttribute()
    {
        return Arr::get(TestResult::getStatusLabels(), $this->status);
    }
}
