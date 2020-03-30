<?php declare(strict_types=1);

namespace App\Casts;

use App\Enums\TestStatusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TestStatusCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return new TestStatusEnum($value);
    }


    public function set($model, string $key, $value, array $attributes)
    {
        return $value->toString();
    }
}
