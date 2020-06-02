<?php declare(strict_types=1);

namespace App\Casts;

use App\Http\Client\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

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
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (!is_array($value)) {
            return $value;
        }

        return new Response(
            new PsrResponse(
                Arr::get($value, 'status'),
                Arr::get($value, 'headers', []),
                json_encode(Arr::get($value, 'body'))
            )
        );
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
        if ($value instanceof Response) {
            $value = $value->toArray();
        }

        if (!is_array($value)) {
            return $value;
        }

        return json_encode($value);
    }
}
