<?php

namespace App\Models\Concerns;

use App\Casts\HttpStreamCast;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait InteractsWithHttpResponse
{
    /**
     * @return void
     */
    protected function initializeInteractsWithHttpResponse()
    {
        $this->fillable = array_merge($this->fillable, [
            'status',
            'headers',
            'body',
        ]);
        $this->casts = array_merge($this->casts, [
            'headers' => 'array',
            'body' => HttpStreamCast::class,
        ]);
    }

    /**
     * @return array|mixed
     */
    public function bodyToArray()
    {
        return json_decode((string) $this->body, true) ?? [];
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
