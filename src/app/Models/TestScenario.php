<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestScenario extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(Component::class, 'test_scenarios_components')
            ->using(TestScenarioComponent::class)
            ->withPivot('position')
            ->orderBy('position');
    }
}
