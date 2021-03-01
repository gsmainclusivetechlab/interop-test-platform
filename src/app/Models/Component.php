<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Pivots\ComponentConnections;
use App\Models\Pivots\TestCaseComponents;
use Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\{
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

    public $timestamps = false;

    /** @var array */
    protected $fillable = ['slug'];

    public function testCases(): BelongsToMany
    {
        return $this->belongsToMany(
            TestCase::class,
            'test_case_components',
            'component_id',
            'test_case_id'
        )
            ->using(TestCaseComponents::class)
            ->withPivot(['component_name', 'component_versions']);
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
        )
            ->using(ComponentConnections::class)
            ->distinct();
    }

    public function getNameAttribute(): string
    {
        if (isset($this->pivot->component_name)) {
            return $this->pivot->component_name;
        }

        if (!($testCase = $this->testCases->first())) {
            return '';
        }

        return $testCase->pivot->component_name .
            (isset($this->pivot->version) ? " {$this->pivot->version}" : '');
    }

    public function deleteWithoutTestCases()
    {
        if ($this->testCases()->doesntExist()) {
            $this->delete();
        }
    }

    public function getExistingVersions(): Collection
    {
        return $this->testCases
            ->pluck('pivot.component_versions')
            ->filter()
            ->flatten()
            ->unique()
            ->values();
    }
}
