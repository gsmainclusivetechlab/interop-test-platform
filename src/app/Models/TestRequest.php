<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\StreamCast;
use App\Casts\UriCast;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
     * @return array
     */
    public function uriToArray()
    {
        return [
            'scheme' => $this->uri->getScheme(),
            'host' => $this->uri->getHost(),
            'port' => $this->uri->getPort(),
            'path' => $this->uri->getPath(),
            'query' => $this->uri->getQuery(),
            'fragment' => $this->uri->getFragment(),
        ];
    }

    /**
     * @return array|mixed
     */
    public function bodyToArray()
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

    /**
     * @return array
     */
    public function attributesToArrayRequest()
    {
        return [
            'method' => $this->method,
            'uri' => $this->uriToArray(),
            'headers' => $this->headers,
            'body' => $this->bodyToArray(),
        ];
    }

    /**
     * @param TestRequestSetup $testRequestSetup
     */
    public function mergeSetup(TestRequestSetup $testRequestSetup)
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
