<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
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
        'forward',
        'backward',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(Component::class, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(Component::class, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function sourceApiService()
    {
        return $this->hasOneThrough(ApiService::class, Component::class, 'id', 'id', 'source_id', 'api_service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function targetApiService()
    {
        return $this->hasOneThrough(ApiService::class, Component::class, 'id', 'id', 'target_id', 'api_service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testScripts()
    {
        return $this->hasMany(TestScript::class, 'test_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testSetups()
    {
        return $this->hasMany(TestSetup::class, 'test_step_id');
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_case_id'];
    }
}
