<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class TestCase extends Model
{
    use HasUuid;

    const BEHAVIOR_NEGATIVE = 'negative';
    const BEHAVIOR_POSITIVE = 'positive';

    /**
     * @var string
     */
    protected $table = 'test_cases';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'public',
        'behavior',
        'description',
        'precondition',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'public' => false,
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('alphabetic', function ($builder) {
            $builder->orderBy('name');
        });

        static::saving(function ($model) {
            $model->attributes['test_case_group_id'] = $model->attributes['test_case_group_id'] ?? rand(1, 999999999);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function useCase()
    {
        return $this->belongsTo(UseCase::class, 'use_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testSteps()
    {
        return $this->hasMany(TestStep::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'group_test_cases',
            'test_case_id',
            'group_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(
            Session::class,
            'session_test_cases',
            'test_case_id',
            'session_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(
            Component::class,
            'test_case_components',
            'test_case_id',
            'component_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRunsWithSoftDeletesTestCases()
    {
        return $this->testRuns()->withoutGlobalScope('session_test_case');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTestRun()
    {
        return $this->hasOne(TestRun::class, 'test_case_id')
            ->completed()
            ->latest();
    }

    /**
     * @return array
     */
    public static function getBehaviorNames()
    {
        return [
            static::BEHAVIOR_NEGATIVE => __('Negative'),
            static::BEHAVIOR_POSITIVE => __('Positive'),
        ];
    }

    /**
     * @return string
     */
    public function getBehaviorNameAttribute()
    {
        return Arr::get(
            static::getBehaviorNames(),
            $this->behavior,
            $this->behavior
        );
    }
}
