<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Pivots\SessionComponent;
use App\Models\Pivots\SessionTestCases;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Str;

/**
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string|null $status
 * @property string|null $reason
 * @property array $environments
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $completed_at
 * @property Carbon $closed_at
 *
 * @property-read bool $completable
 * @property-read string|null $status_name
 *
 * @property User $owner
 * @property TestCase[]|Collection $testCases
 * @property Component[]|Collection $components
 * @property FileEnvironment[]|Collection $fileEnvironments
 * @property TestStep[]|Collection $testSteps
 * @property SimulatorPlugin $simulatorPlugin
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
        'use_encryption',
        'group_environment_id',
        'simulator_plugin_id',
        'environments',
        'completed_at',
        'closed_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'environments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsTo
     */
    public function groupEnvironment()
    {
        return $this->belongsTo(
            GroupEnvironment::class,
            'group_environment_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(
            Component::class,
            'session_components',
            'session_id',
            'component_id'
        )
            ->using(SessionComponent::class)
            ->withPivot([
                'base_url',
                'use_encryption',
                'certificate_id',
                'implicit_sut_id',
                'version',
            ]);
    }

    /**
     * @return HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'session_id');
    }

    /**
     * @return HasManyThrough
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
     * @return HasMany
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
     * @return BelongsToMany
     */
    public function testCases()
    {
        return $this->belongsToMany(
            TestCase::class,
            'session_test_cases',
            'session_id',
            'test_case_id'
        )
            ->using(SessionTestCases::class)
            ->withPivot(['deleted_at'])
            ->wherePivot('deleted_at', null);
    }

    /**
     * @return BelongsToMany
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
     * @return BelongsToMany
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
     * @return HasMany
     */
    public function questionnaireAnswers()
    {
        return $this->hasMany(QuestionnaireAnswer::class);
    }

    public function fileEnvironments(): MorphMany
    {
        return $this->morphMany(FileEnvironment::class, 'environmentable');
    }

    public function simulatorPlugin(): BelongsTo
    {
        return $this->belongsTo(SimulatorPlugin::class);
    }

    public function environments(): array
    {
        return array_merge(
            $this->environments ?? [],
            $this->fileEnvironments->pluck('path', 'name')->all()
        );
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
     * @param string|null $default
     * @param bool $forResolver
     *
     * @return string
     */
    public function getBaseUriOfComponent(
        Component $component = null,
        $default = null,
        $forResolver = false
    ) {
        $url = Arr::get(
            $component
                ? $this->components()
                    ->whereKey($component->getKey())
                    ->first()
                : [],
            'pivot.base_url',
            $default
        );

        return $url && $forResolver ? Str::finish($url, '/') : $url;
    }

    /**
     * @return array
     */
    public static function getTypeNames()
    {
        return [
            static::TYPE_TEST => __('Test'),
            static::TYPE_COMPLIANCE => __('Certification'),
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
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return Arr::get(static::getTypeNames(), $this->type);
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
     * @param string|null $timestampField
     *
     * @return bool
     */
    public function updateStatus($status, $timestampField = null): bool
    {
        $timestamp = $timestampField ? [$timestampField => now()] : [];

        return $this->update(['status' => $status] + $timestamp);
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

        return !$this->testCases()
            ->whereDoesntHave('lastTestRun', function (Builder $query) {
                $query->where('session_id', $this->id);
            })
            ->exists();
    }

    /**
     * @param TestCase $testCase
     *
     * @return bool
     */
    public function isAvailableTestCaseRun(TestCase $testCase): bool
    {
        $testRunsCount = $this->testRuns()
            ->where('test_case_id', $testCase->id)
            ->count();

        return !$this->isComplianceSession() ||
            ($this->isAvailableToUpdate() &&
                $testRunsCount <
                    config(
                        'service_session.compliance_session_execution_limit'
                    ));
    }

    public static function getMappedUrls(
        $components,
        Session $session = null,
        Group $group = null
    ): Collection {
        $useEncryptionComponentIds = $session
            ? $session->components->where('pivot.use_encryption')->pluck('id')
            : collect(session('session.sut'))
                ->where('use_encryption', '1')
                ->keys();

        $groupPrefix = $group ? '-group' : '';

        return $components->mapWithKeys(function (Component $component) use (
            $useEncryptionComponentIds,
            $groupPrefix,
            $session,
            $group
        ) {
            $secure = $useEncryptionComponentIds->contains($component->id);

            return [
                $component->slug => $component->connections->mapWithKeys(
                    function (Component $connection) use (
                        $component,
                        $secure,
                        $groupPrefix,
                        $group,
                        $session
                    ) {
                        $url = route(
                            $secure
                                ? "testing{$groupPrefix}.sut"
                                : "testing-insecure{$groupPrefix}.sut",
                            [
                                $component->slug,
                                $connection->slug,
                                $group->id ??
                                ($session->uuid ??
                                    session('session.info.uuid')),
                            ],
                            false
                        );

                        return [
                            $connection->slug =>
                                ($secure
                                    ? config('app.testing_url_https')
                                    : config('app.testing_url_http')) . $url,
                        ];
                    }
                ),
            ];
        });
    }
}
