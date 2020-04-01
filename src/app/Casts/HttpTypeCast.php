<?php declare(strict_types=1);

namespace App\Casts;

use App\Enums\HttpTypeEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class HttpTypeCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return HttpTypeEnum|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return new HttpTypeEnum($value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
