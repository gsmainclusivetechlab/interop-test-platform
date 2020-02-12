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

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function suites()
//    {
//        return $this->hasMany(TestSuite::class);
//    }
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
