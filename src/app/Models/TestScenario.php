<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestScenario extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_scenarios';

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
    public function platforms()
    {
        return $this->hasMany(TestPlatform::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function platformsConnections()
    {
        return $this->hasMany(TestPlatformConnection::class, 'scenario_id');
    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function components()
//    {
//        return $this->belongsToMany(TestComponent::class)
//            ->using(TestComponentTestScenario::class)
//            ->withPivot('position')
//            ->orderBy('position');
//    }
}
