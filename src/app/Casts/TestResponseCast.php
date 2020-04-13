<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class TestResponseCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = json_decode($value, true);

        return new TestResponse(new Response(
            Arr::get($value, 'status'),
            Arr::get($value, 'headers'),
            json_encode(Arr::get($value, 'body'))
        ));
    }


    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof TestResponse) {
            return $value;
        }

        return json_encode($value->toArray());
    }
}
