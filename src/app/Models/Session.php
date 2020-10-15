<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin \Eloquent
 */
class Session extends Model
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'sessions';

    /**
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'group_environment_id',
        'environments',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'environments' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupEnvironment()
    {
        return $this->belongsTo(
            GroupEnvironment::class,
            'group_environment_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(
            Component::class,
            'session_components',
            'session_id',
            'component_id'
        )->withPivot(['base_url']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function testResults()
    {
        return $this->hasManyThrough(
            TestResult::class,
            TestRun::class,
            'session_id',
            'test_run_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageLog()
    {
        return $this->hasMany(MessageLog::class, 'session_id');
    }

    /**
     * @return mixed
     */
    public function lastTestRun()
    {
        return $this->hasOne(TestRun::class, 'session_id')
            ->completed()
            ->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testCases()
    {
        return $this->belongsToMany(
            TestCase::class,
            'session_test_cases',
            'session_id',
            'test_case_id'
        )
            ->withPivot(['deleted_at'])
            ->wherePivot('deleted_at', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testCasesWithSoftDeletes()
    {
        return $this->belongsToMany(
            TestCase::class,
            'session_test_cases',
            'session_id',
            'test_case_id'
        )->withPivot(['deleted_at']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function testSteps()
    {
        return $this->belongsToMany(
            TestStep::class,
            'session_test_cases',
            'session_id',
            'test_case_id',
            'id',
            'test_case_id'
        )
            ->wherePivot('deleted_at', null);
    }

    /**
     * @return array
     */
    public function environments()
    {
        return array_merge($this->environments, [
            'SP_BASE_URI' => $this->getBaseUriOfComponent(
                Component::where('name', 'Service Provider')->firstOrFail()
            ),
            'MMO1_BASE_URI' => $this->getBaseUriOfComponent(
                Component::where(
                    'name',
                    'Mobile Money Operator 1'
                )->firstOrFail()
            ),
            'MOJALOOP_BASE_URI' => $this->getBaseUriOfComponent(
                Component::where('name', 'Mojaloop')->firstOrFail()
            ),
            'MMO2_BASE_URI' => $this->getBaseUriOfComponent(
                Component::where(
                    'name',
                    'Mobile Money Operator 2'
                )->firstOrFail()
            ),
            'CURRENT_TIMESTAMP_ISO8601' => now()->toIso8601String(),
            'CURRENT_TIMESTAMP_RFC2822' => now()->toRfc2822String(),
        ]);
    }

    /**
     * @param Component $component
     * @return bool
     */
    public function hasComponent(Component $component)
    {
        return $this->components()
            ->whereKey($component->getKey())
            ->exists();
    }

    /**
     * @param Component $component
     * @return string
     */
    public function getBaseUriOfComponent(Component $component)
    {
        return Arr::get(
            $this->components()
                ->whereKey($component->getKey())
                ->first(),
            'pivot.base_url',
            $component->base_url
        );
    }
}
