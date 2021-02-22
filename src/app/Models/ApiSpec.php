<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use cebe\openapi\spec\OpenApi;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 *
 * @property string $name
 * @property OpenApi $openapi
 */
class ApiSpec extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_specs';

    /**
     * @var array
     */
    protected $fillable = ['name', 'openapi', 'description'];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];
}
