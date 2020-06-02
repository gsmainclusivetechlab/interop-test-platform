<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTestRun()
    {
        return $this->hasOne(TestRun::class, 'test_case_id')
            ->completed()
            ->latest();
    }
}
