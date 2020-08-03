<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class MessageLog extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'message_log';

    /**
     * @var array
     */
    protected $fillable = ['request', 'exception'];

    /**
     * @var array
     */
    protected $casts = [
        'request' => RequestCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testStep()
    {
        return $this->belongsTo(TestStep::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testRun()
    {
        return $this->belongsTo(TestRun::class, 'test_run_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->hasOneThrough(
            TestCase::class,
            TestRun::class,
            'id',
            'id',
            'test_run_id',
            'test_case_id'
        );
    }
}
