<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestResult extends Model
{
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
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'total' => 0,
        'passed' => 0,
        'failures' => 0,
        'errors' => 0,
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            $model->total = $model->testStep->testRequestScripts()->count() + $model->testStep->testResponseScripts()->count();
        });
    }

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
//    public function testScripts()
//    {
//        return $this->hasManyThrough(TestScript::class, TestStep::class, 'id', 'test_step_id', 'test_step_id', 'id');
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testExecutions()
    {
        return $this->hasMany(TestExecution::class, 'test_result_id');
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->passed >= $this->total && !$this->failures && !$this->errors;
    }
}
