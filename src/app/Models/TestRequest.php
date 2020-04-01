<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\StreamCast;
use App\Casts\UriCast;
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
        'headers',
        'body',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'uri' => UriCast::class,
        'headers' => 'array',
        'body' => StreamCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @return string
     */
    public function getPathAttribute()
    {
        return $this->uri->getPath();
    }

    /**
     * @return array|false|string
     */
    public function getJsonAttribute()
    {
        return json_decode((string) $this->body, true) ?? [];
    }

    /**
     * @param RequestInterface $request
     * @return self
     */
    public static function makeFromRequest(RequestInterface $request)
    {
        return static::make([
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody(),
        ]);
    }

    /**
     * @return Request
     */
    public function toRequest()
    {
        return new Request($this->method, $this->uri, $this->headers, $this->body);
    }
}
