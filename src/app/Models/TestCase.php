<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestCase extends Model
{
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
        'description',
        'precondition',
        'behavior',
    ];

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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositive($query)
    {
        return $query->where('behavior', static::BEHAVIOR_POSITIVE);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNegative($query)
    {
        return $query->where('behavior', static::BEHAVIOR_NEGATIVE);
    }
}
