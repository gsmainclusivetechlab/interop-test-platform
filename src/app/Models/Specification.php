<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Specification extends Model
{
    /**
     * @var string
     */
    protected $table = 'specifications';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'version',
        'openapi',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];
}
