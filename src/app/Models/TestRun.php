<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRun extends Model
{
    use HasUuid;

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
    protected $attributes = [
        'total' => 0,
        'successful' => 0,
        'unsuccessful' => 0,
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            $model->total = $model->testSteps()->count();
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
    public function scopePassed($query)
    {
        return $query->whereColumn('total', '=', 'successful');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailures($query)
    {
        return $query->whereColumn('total', '!=', 'successful');
    }

    /**
     * @return string
     */
    public function getTraceIdAttribute()
    {
        return str_replace('-', '', $this->uuid);
    }
}
