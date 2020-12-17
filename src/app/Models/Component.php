<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $base_url
 */
class Component extends Model
{
    use HasUuid;
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'components';

    protected $attributes = [
        'sutable' => true,
    ];

    /**
     * @var array
     */
    protected $fillable = ['name', 'base_url', 'description', 'sutable'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testCases()
    {
        return $this->belongsToMany(
            TestCase::class,
            'test_case_components',
            'component_id',
            'test_case_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sourceTestSteps()
    {
        return $this->hasMany(TestStep::class, 'source_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function targetTestSteps()
    {
        return $this->hasMany(TestStep::class, 'target_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function connections()
    {
        return $this->belongsToMany(
            static::class,
            'component_connections',
            'source_id',
            'target_id'
        );
    }
}
