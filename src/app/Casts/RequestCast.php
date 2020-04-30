<?php declare(strict_types=1);

namespace App\Casts;

use App\Http\Client\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class RequestCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Request|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (!is_array($value)) {
            return $value;
        }

        return new Request(new ServerRequest(
                Arr::get($value, 'method'),
                Arr::get($value, 'uri'),
                Arr::get($value, 'headers', []),
                json_encode(Arr::get($value, 'body'))
            ));
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
        if ($value instanceof Request) {
            $value = $value->toArray();
        }

        if (!is_array($value)) {
            return $value;
        }

        return json_encode($value);
    }
}
