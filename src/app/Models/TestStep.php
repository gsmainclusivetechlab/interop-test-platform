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
        'request_rules',
        'response_rules',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request_rules' => 'array',
        'response_rules' => 'array',
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
        return $this->belongsTo(TestPlatform::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sourceSpecification()
    {
        return $this->hasOneThrough(Specification::class, TestPlatform::class, 'id', 'id', 'source_id', 'specification_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(TestPlatform::class, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function targetSpecification()
    {
        return $this->hasOneThrough(Specification::class, TestPlatform::class, 'id', 'id', 'target_id', 'specification_id');
    }
}
