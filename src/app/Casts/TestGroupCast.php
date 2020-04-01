<?php declare(strict_types=1);

namespace App\Casts;

use App\Enums\TestGroupEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TestGroupCast implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return new TestGroupEnum($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
