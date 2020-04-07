<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Api extends Model
{
    /**
     * @var string
     */
    protected $table = 'apis';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'scheme',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'scheme' => OpenApiCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiServers()
    {
        return $this->hasMany(ApiServer::class, 'api_id');
    }
}
