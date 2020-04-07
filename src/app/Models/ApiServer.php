<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class ApiServer extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_servers';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'base_url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api()
    {
        return $this->belongsTo(Api::class, 'api_id');
    }
}
