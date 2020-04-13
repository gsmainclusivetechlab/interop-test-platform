<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestRequest;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class TestRequestCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return TestRequest|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (!is_array($value)) {
            return $value;
        }

        return new TestRequest(new Request(
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
        if ($value instanceof TestRequest) {
            $value = $value->toArray();
        }

        if (!is_array($value)) {
            return $value;
        }

        return json_encode($value);
    }
}
