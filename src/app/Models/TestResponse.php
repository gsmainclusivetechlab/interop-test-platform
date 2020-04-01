<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\StreamCast;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

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
     * @return array|false|string
     */
    public function getJsonAttribute()
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
}
