<?php declare(strict_types=1);

namespace App\Models;

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
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiEndpoints()
    {
        return $this->hasMany(ApiEndpoint::class, 'api_service_id');
    }
}
