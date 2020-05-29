<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestExecution extends Model
{
    const UPDATED_AT = null;

    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';

    /**
     * @var string
     */
    protected $table = 'test_executions';

    /**
     * @var array
     */
    protected $fillable = ['name', 'actual', 'expected', 'exception', 'status'];

    /**
     * @var array
     */
    protected $casts = [
        'actual' => 'array',
        'expected' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @return bool
     */
    public function getSuccessfulAttribute()
    {
        return $this->status === static::STATUS_PASS;
    }
}
