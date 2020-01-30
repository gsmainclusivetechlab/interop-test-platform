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
        return $this->belongsToMany(Component::class)
            ->using(ComponentTestScenario::class)
            ->withPivot('position')
            ->orderBy('position');
    }
}
