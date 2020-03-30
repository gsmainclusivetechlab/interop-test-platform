<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\RequestInterface;

/**
 * @mixin \Eloquent
 */
class TestRequest extends Model
{
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
     * @var array
     */
    protected $fillable = [
        'method',
        'uri',
        'headers',
        'body',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'headers' => 'array',
        'body' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @param RequestInterface $request
     * @return self
     */
    public static function makeFromRequest(RequestInterface $request)
    {
        return static::make([
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => json_decode((string) $request->getBody(), true),
        ]);
    }
}
