<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Component extends Model
{
    use HasUuid;
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'components';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'base_url',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function connections()
    {
        return $this->belongsToMany(static::class, Connection::class, 'source_id', 'target_id')
            ->withPivot(['specification_id']);
    }
}
