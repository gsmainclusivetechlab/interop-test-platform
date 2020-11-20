<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $status
 * @property string|null $reason
 *
 * @property-read bool $completable
 * @property-read string|null $status_name
 *
 * @property User $owner
 * @property TestCase[]|Collection $testCases
 */
class Session extends Model
{
    use HasUuid;

    const TYPE_TEST = 'test';
    const TYPE_COMPLIANCE = 'compliance';

    const STATUS_READY = 'ready';
    const STATUS_IN_EXECUTION = 'in_execution';
    const STATUS_IN_VERIFICATION = 'in_verification';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

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
        'type',
        'status',
        'reason',
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
        )->wherePivot('deleted_at', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionnaireAnswers()
    {
        return $this->hasMany(QuestionnaireAnswer::class);
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
     *
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
     *
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

    /**
     * @return array
     */
    public static function getTypeNames()
    {
        return [
            static::TYPE_TEST => __('Test'),
            static::TYPE_COMPLIANCE => __('Compliance'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            static::STATUS_READY => __('Ready'),
            static::STATUS_IN_EXECUTION => __('In Execution'),
            static::STATUS_IN_VERIFICATION => __('In Verification'),
            static::STATUS_APPROVED => __('Approved'),
            static::STATUS_DECLINED => __('Declined'),
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function getStatusName($type)
    {
        return $type ? Arr::get(static::getStatusNames(), $type) : null;
    }

    /**
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return static::getStatusName($this->status);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isCompliance($type): bool
    {
        return $type == static::TYPE_COMPLIANCE;
    }

    /**
     * @return bool
     */
    public function isComplianceSession(): bool
    {
        return static::isCompliance($this->type);
    }

    /**
     * @return bool
     */
    public function isStatusReady(): bool
    {
        return $this->status == static::STATUS_READY;
    }

    /**
     * @return bool
     */
    public function isStatusInExecution(): bool
    {
        return $this->status == static::STATUS_IN_EXECUTION;
    }

    /**
     * @return bool
     */
    public function isStatusInVerification(): bool
    {
        return $this->status == static::STATUS_IN_VERIFICATION;
    }

    /**
     * @return bool
     */
    public function isStatusApproved(): bool
    {
        return $this->status == static::STATUS_APPROVED;
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public function updateStatus($status): bool
    {
        return $this->update(['status' => $status]);
    }

    /**
     * @return bool
     */
    public function isAvailableToUpdate(): bool
    {
        return !in_array($this->status, [
            static::STATUS_IN_VERIFICATION,
            static::STATUS_APPROVED,
            static::STATUS_DECLINED,
        ]);
    }

    /**
     * @return bool
     */
    public function getCompletableAttribute(): bool
    {
        if (!$this->isComplianceSession() || !$this->isStatusInExecution()) {
            return false;
        }

        $testCases = $this->testCases()
            ->with('lastTestRun')
            ->get();

        /** @var TestCase $testCase */
        foreach ($testCases as $testCase) {
            if (
                !$testCase->lastTestRun ||
                !$testCase->lastTestRun->successful
            ) {
                return false;
            }
        }

        return true;
    }
}
