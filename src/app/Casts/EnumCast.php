<?php declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EnumCast implements CastsAttributes
{
    /**
     * @var string
     */
    protected $enum;

    /**
     * @param string $enum
     */
    public function __construct(string $enum)
    {
        $this->enum = $enum;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return new $this->enum($value);
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
