<?php declare(strict_types=1);

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Psr7\stream_for;

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
        'path',
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
    public static function makeFromPsr(RequestInterface $request)
    {
        return static::make([
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'path' => $request->getUri()->getPath(),
            'headers' => $request->getHeaders(),
            'body' => json_decode((string) $request->getBody(), true) ?? [],
        ]);
    }

    /**
     * @return Request
     */
    public function toPsr()
    {
        return new Request($this->method, $this->uri, $this->headers, stream_for(json_encode($this->body)));
    }
}
