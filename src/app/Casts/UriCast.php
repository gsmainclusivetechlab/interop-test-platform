<?php declare(strict_types=1);

namespace App\Casts;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UriCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return new Uri($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
