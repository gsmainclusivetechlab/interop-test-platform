<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use App\Scopes\PositionScope;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestPlatform extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_platforms';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'sut',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'sut' => false,
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PositionScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(TestScenario::class, 'scenario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specification()
    {
        return $this->belongsTo(Specification::class, 'specification_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function connections()
    {
        return $this->belongsToMany(static::class, 'test_platforms_connections', 'source_id', 'target_id')
            ->using(TestPlatformConnection::class)
            ->withPivot('simulated');
    }
}
