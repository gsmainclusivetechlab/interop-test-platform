<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestPlatformConnection extends Model
{
    const CONNECTION_SIMULATED = 'simulated';
    const CONNECTION_NOT_SIMULATED = 'not-simulated';

    /**
     * @var string
     */
    protected $table = 'test_platforms_connections';

    /**
     * @var array
     */
    protected $fillable = [
        'connection',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(TestPlatform::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(TestPlatform::class, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(TestScenario::class, 'scenario_id');
    }
}
