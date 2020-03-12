<?php declare(strict_types=1);

namespace App\Casts;

use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class RequestCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new ServerRequest(...json_decode($value, true));
    }

    public function set($model, string $key, $value, array $attributes)
    {
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
