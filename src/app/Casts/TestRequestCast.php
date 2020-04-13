<?php declare(strict_types=1);

namespace App\Casts;

use App\Testing\TestRequest;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class TestRequestCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = json_decode($value, true);

        return new TestRequest(new Request(
            Arr::get($value, 'method'),
            Arr::get($value, 'uri'),
            Arr::get($value, 'headers'),
            json_encode(Arr::get($value, 'body'))
        ));
    }


    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof TestRequest) {
            return $value;
        }

        return json_encode($value->toArray());
    }
}
