<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
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
        'preconditions',
        'behavior',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(TestStep::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo(TestSuite::class, 'suite_id');
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
