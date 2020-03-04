<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin \Eloquent
 */
class TestConnection extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'test_connections';

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
        return $this->belongsTo(TestComponent::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(TestComponent::class, 'target_id');
    }
}
