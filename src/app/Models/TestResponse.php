<?php declare(strict_types=1);

namespace App\Models;

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
     * @param ResponseInterface $response
     * @return self
     */
    public static function makeFromPsr(ResponseInterface $response)
    {
        return static::make([
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => json_decode((string) $response->getBody(), true) ?? [],
        ]);
    }

    /**
     * @return Response
     */
    public function toPsr()
    {
        return new Response($this->status, $this->headers, stream_for(json_encode($this->body)));
    }
}
