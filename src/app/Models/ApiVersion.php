<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiVersion extends Model
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'api_versions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'server',
        'openapi',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
