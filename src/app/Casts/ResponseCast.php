<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ResponseCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Response|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (!is_string($value)) {
            return $value;
        }

        return new TestResponse(new Response(...json_decode($value, true)));
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
        if (!$value instanceof TestResponse) {
            return $value;
        }

        return json_encode([
            $value->status(),
            $value->headers(),
            $value->body(),
        ]);
    }
}
