<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiScheme extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_schemes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'openapi',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];
}
