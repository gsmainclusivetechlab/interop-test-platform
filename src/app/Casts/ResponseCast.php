<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestResponse;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ResponseCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return TestResponse|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_string($value)) {
            return TestResponse::fromParts(json_decode($value, true));
        } else {
            return $value;
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return TestResponse|array|mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            return TestResponse::fromParts($value);
        } else {
            return $value;
        }
    }
}
