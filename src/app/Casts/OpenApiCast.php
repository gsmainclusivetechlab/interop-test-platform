<?php declare(strict_types=1);

namespace App\Casts;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\Writer;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class OpenApiCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return OpenApi
     * @throws \cebe\openapi\exceptions\TypeErrorException
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (!is_string($value)) {
            return $value;
        }

        return Reader::readFromJson($value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof OpenApi) {
            return $value;
        }

        return Writer::writeToJson($value);
    }
}
