<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestRequest;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class RequestCast implements CastsAttributes
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
            return TestRequest::fromParts(json_decode($value, true));
        } else {
            return $value;
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return TestRequest|array|mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            return TestRequest::fromParts($value);
        } else {
            return $value;
        }
    }
}
