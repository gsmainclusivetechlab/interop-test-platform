<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestRequest;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

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
        if (!is_string($value)) {
            return $value;
        }

        return new TestRequest(new Request(...json_decode($value, true)));
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
        if (!$value instanceof TestRequest) {
            return $value;
        }

        return json_encode([
            $value->method(),
            $value->url(),
            $value->headers(),
            $value->body(),
        ]);
    }
}
