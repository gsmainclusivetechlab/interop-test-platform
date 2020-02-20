<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Eloquent
 */
class TestPlatformConnection extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'test_platforms_connections';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'simulated',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'simulated' => false,
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
}
