<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiService extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_services';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'server',
        'scheme',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'scheme' => OpenApiCast::class,
    ];
}
