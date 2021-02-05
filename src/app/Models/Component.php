<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Eloquent;
use Illuminate\Database\Eloquent\{
    Collection,
    Model,
    Relations\BelongsToMany,
    Relations\HasMany
};

/**
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 *
 * @property TestCase[]|Collection $testCases
 * @property TestStep[]|Collection $sourceTestSteps
 * @property TestStep[]|Collection $targetTestSteps
 * @property Component[]|Collection $connections
 */
class Component extends Model
{
    use HasSlug;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    /** @var array */
    protected $fillable = ['slug'];

    public function testCases(): BelongsToMany
    {
        return $this->belongsToMany(
            TestCase::class,
            'test_case_components',
            'component_id',
            'test_case_id'
        )->withPivot(['component_name', 'component_versions']);
    }

    public function sourceTestSteps(): HasMany
    {
        return $this->hasMany(TestStep::class, 'source_id', 'id');
    }

    public function targetTestSteps(): HasMany
    {
        return $this->hasMany(TestStep::class, 'target_id', 'id');
    }

    public function connections(): BelongsToMany
    {
        return $this->belongsToMany(
            static::class,
            'test_steps',
            'source_id',
            'target_id'
        )->distinct();
    }

    public function getNameAttribute(): string
    {
        return $this->testCases->first()->pivot->component_name .
            (isset($this->pivot->version) ? " {$this->pivot->version}" : '');
    }
}
