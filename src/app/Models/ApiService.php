<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiService extends Model
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'api_services';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'version',
        'description',
        'server',
        'openapi',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];
}
