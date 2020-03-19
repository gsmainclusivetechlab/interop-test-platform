<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Scenario extends Model
{
    /**
     * @var string
     */
    protected $table = 'scenarios';

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
    public function useCases()
    {
        return $this->hasMany(UseCase::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testCases()
    {
        return $this->hasManyThrough(TestCase::class, UseCase::class, 'scenario_id', 'use_case_id', 'id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function components()
    {
        return $this->hasMany(Component::class, 'scenario_id');
    }
}
