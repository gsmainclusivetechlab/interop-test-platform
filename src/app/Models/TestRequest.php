<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestRequest extends Model
{
    use InteractsWithHttpRequest;

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_requests';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @param TestSetup $testRequestSetup
     */
    public function mergeSetup(TestSetup $testRequestSetup)
    {
        $attributes = $this->attributesToArrayRequest();

        foreach ($testRequestSetup->values as $key => $value) {
            Arr::set($attributes, $key, $value);
        }

        $this->setAttribute('method', $attributes['method']);
        $this->setAttribute('uri', $attributes['uri']);
        $this->setAttribute('headers', $attributes['headers']);
        $this->setAttribute('body', $attributes['body']);
    }
}
