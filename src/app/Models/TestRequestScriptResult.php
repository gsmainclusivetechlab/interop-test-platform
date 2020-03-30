<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRequestScriptResult extends Model
{
    const UPDATED_AT = null;

    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';
    const STATUS_ERROR = 'error';

    /**
     * @var string
     */
    protected $table = 'test_request_script_results';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testRequest()
    {
        return $this->belongsTo(TestRequest::class, 'test_result_id');
    }
}
