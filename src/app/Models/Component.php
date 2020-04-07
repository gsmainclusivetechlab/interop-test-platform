<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPositionAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Component extends Model
{
    use HasPositionAttribute;

    /**
     * @var string
     */
    protected $table = 'components';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'sut',
        'simulated',
        'api_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'sut' => false,
        'simulated' => false,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api()
    {
        return $this->belongsTo(Api::class, 'api_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paths()
    {
        return $this->belongsToMany(static::class, 'component_paths', 'source_id', 'target_id');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['scenario_id'];
    }
}
