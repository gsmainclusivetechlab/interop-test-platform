<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestScenario extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
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
