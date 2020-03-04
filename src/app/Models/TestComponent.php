<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestComponent extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_components';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(TestScenario::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function platform()
    {
        return $this->hasOne(TestPlatform::class, 'component_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function connections()
    {
        return $this->belongsToMany(static::class, 'test_connections', 'source_id', 'target_id')
            ->using(TestConnection::class)
            ->withPivot('simulated');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['scenario_id'];
    }
}
