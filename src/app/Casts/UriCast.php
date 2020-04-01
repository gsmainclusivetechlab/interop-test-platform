<?php declare(strict_types=1);

namespace App\Casts;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UriCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            return Uri::fromParts($value);
        } else {
            return new Uri($value);
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            $value = Uri::fromParts($value);
        }

        return $value;
    }
}
