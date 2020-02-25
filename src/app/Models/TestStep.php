<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use App\Scopes\PositionScope;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestStep extends Model
{
    use HasPosition;

    /**
     * @var string
     */
    protected $table = 'test_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'path',
        'method',
        'expected_request',
        'expected_response',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'expected_request' => 'array',
        'expected_response' => 'array',
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
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

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
