<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\HttpStreamCast;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

/**
 * @mixin \Eloquent
 */
class TestResponse extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_responses';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'headers',
        'body',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'headers' => 'array',
        'body' => HttpStreamCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'test_result_id');
    }

    /**
     * @return array|mixed
     */
    public function bodyToArray()
    {
        return json_decode((string) $this->body, true) ?? [];
    }

    /**
     * @param ResponseInterface $response
     * @return self
     */
    public static function makeFromResponse(ResponseInterface $response)
    {
        return static::make([
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody(),
        ]);
    }

    /**
     * @return Response
     */
    public function toResponse()
    {
        return new Response($this->status, $this->headers, $this->body);
    }

    /**
     * @return array
     */
    public function attributesToArrayResponse()
    {
        return [
            'status' => $this->status,
            'headers' => $this->headers,
            'body' => $this->bodyToArray(),
        ];
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
        $this->setAttribute('body', $attributes['body']);
    }
}
