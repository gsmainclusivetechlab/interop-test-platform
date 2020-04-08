<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestResponse extends Model
{
    use InteractsWithHttpResponse;

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_responses';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @param TestSetup $testResponseSetup
     */
    public function mergeSetup(TestSetup $testResponseSetup)
    {
        $attributes = $this->attributesToArrayResponse();

        foreach ($testResponseSetup->values as $key => $value) {
            Arr::set($attributes, $key, $value);
        }

        $this->setAttribute('status', $attributes['status']);
        $this->setAttribute('headers', $attributes['headers']);
        $this->setAttribute('body', json_encode($attributes['body']));
    }
}
