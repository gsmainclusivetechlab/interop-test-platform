<?php declare(strict_types=1);

namespace App\Casts;

use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Psr\Http\Message\ServerRequestInterface;

class RequestCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return ServerRequest|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (!is_string($value)) {
            return $value;
        }

        return new ServerRequest(...json_decode($value, true));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|false|mixed|string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof ServerRequestInterface) {
            return $value;
        }

        return json_encode([
            $value->getMethod(),
            $value->getUri()->__toString(),
            $value->getHeaders(),
            $value->getBody()->__toString(),
            $value->getProtocolVersion(),
            $value->getServerParams(),
        ]);
    }
}
