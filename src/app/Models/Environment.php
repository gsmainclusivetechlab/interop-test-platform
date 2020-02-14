<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

/**
 * @mixin Eloquent
 */
class Environment extends Model
{
    /**
     * @var string
     */
    protected $table = 'environments';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'variables',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            $model->variables = Yaml::parse($model->variables);
        });
    }
}
