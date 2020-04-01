<?php declare(strict_types=1);

namespace App\Casts;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class HttpUriCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Uri|mixed|\Psr\Http\Message\UriInterface
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            return Uri::fromParts($value);
        } else {
            return new Uri($value);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|mixed|\Psr\Http\Message\UriInterface
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            $value = Uri::fromParts($value);
        }

        return $value;
    }
}
