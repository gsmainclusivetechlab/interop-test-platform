<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiEndpoint extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_endpoints';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'route',
        'method',
        'description',
    ];
}
