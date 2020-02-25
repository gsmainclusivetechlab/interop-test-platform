<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Eloquent
 */
class TestRun extends Model
{
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
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->trace_id = str_replace('-', '', Str::uuid());
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function steps()
    {
        return $this->hasManyThrough(TestStep::class, TestCase::class, 'id', 'case_id', 'case_id', 'id');
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
}
