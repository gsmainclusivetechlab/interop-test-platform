<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
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
        'path',
        'method',
        'pattern',
        'trigger',
        'request',
        'response',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'trigger' => 'array',
        'request' => RequestCast::class,
        'response' => ResponseCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testRuns()
    {
        return $this->hasManyThrough(
            TestRun::class,
            TestCase::class,
            'id',
            'test_case_id',
            'test_case_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'test_step_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiSpec()
    {
        return $this->belongsTo(ApiSpec::class, 'api_spec_id');
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

    /**
     * @return array
     */
    public static function getMethodList()
    {
        return [
            'GET' => 'GET',
            'POST' => 'POST',
            'PUT' => 'PUT',
            'PATCH' => 'PATCH',
            'DELETE' => 'DELETE'
        ];
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            200 => '200 (OK)',
            202 => '202 (Accepted)',
            204 => '204 (No Content)',
            400 => '400 (Bad Request)',
            401 => '401 (Unauthorized)',
            403 => '403 (Forbidden)',
            404 => '404 (Not Found)',
            500 => '500 (Internal Server Error)',
            503 => '503 (Service Unavailable)',
        ];
    }
}
