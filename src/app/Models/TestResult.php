<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestResult extends Model
{
    use HasPosition;

    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_PASSED = 'passed';
    const STATUS_ERROR = 'error';
    const STATUS_FAILURE = 'failure';

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [
        'step_id',
        'time',
        'status',
        'request',
        'response',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_INCOMPLETE,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function run()
    {
        return $this->belongsTo(TestRun::class, 'run_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(TestStep::class, 'step_id');
    }

}
