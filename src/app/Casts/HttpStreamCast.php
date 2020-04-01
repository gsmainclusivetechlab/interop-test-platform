<?php declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use function GuzzleHttp\Psr7\stream_for;

class HttpStreamCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed|\Psr\Http\Message\StreamInterface
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return stream_for($value);
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
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
