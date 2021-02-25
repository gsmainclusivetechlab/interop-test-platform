<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPosition;
use App\Models\Concerns\HasUuid;
use App\Models\Pivots\SessionTestCases;
use App\Models\Pivots\TestCaseComponents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 *
 * @property-read TestCase $last_available_version
 *
 * @property TestRun $lastTestRun
 * @property TestRun[]|Collection $testRuns
 * @property Component[]|Collection $components
 */
class TestCase extends Model
{
    use HasUuid;
    use HasPosition;

    const BEHAVIOR_NEGATIVE = 'negative';
    const BEHAVIOR_POSITIVE = 'positive';

    /**
     * @var string
     */
    protected $table = 'test_cases';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'public',
        'draft',
        'behavior',
        'description',
        'precondition',
        'use_case_id',
        'test_case_group_id',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'public' => false,
        'draft' => false,
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('alphabetic', function ($builder) {
            $builder->orderBy('name');
        });

        static::saving(function ($model) {
            $model->attributes['test_case_group_id'] =
                $model->attributes['test_case_group_id'] ?? Str::random();

            session()->forget('session');
        });
    }

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
    public function useCase()
    {
        return $this->belongsTo(UseCase::class, 'use_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testSteps()
    {
        return $this->hasMany(TestStep::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'group_test_cases',
            'test_case_id',
            'group_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(
            Session::class,
            'session_test_cases',
            'test_case_id',
            'session_id'
        )->using(SessionTestCases::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(
            Component::class,
            'test_case_components',
            'test_case_id',
            'component_id'
        )
            ->using(TestCaseComponents::class)
            ->withPivot(['component_name', 'component_versions']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRuns()
    {
        return $this->hasMany(TestRun::class, 'test_case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testRunsWithSoftDeletesTestCases()
    {
        return $this->testRuns()->withoutGlobalScope('session_test_case');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTestRun()
    {
        return $this->hasOne(TestRun::class, 'test_case_id')
            ->completed()
            ->latest();
    }

    /**
     * @return array
     */
    public static function getBehaviorNames()
    {
        return [
            static::BEHAVIOR_NEGATIVE => __('Negative'),
            static::BEHAVIOR_POSITIVE => __('Positive'),
        ];
    }

    /**
     * @return string
     */
    public function getBehaviorNameAttribute()
    {
        return Arr::get(
            static::getBehaviorNames(),
            $this->behavior,
            $this->behavior
        );
    }

    /**
     * @return array
     */
    public function getPositionGroupColumn()
    {
        return ['test_case_group_id'];
    }

    /**
     * @return string
     */
    public function getPositionColumn()
    {
        return 'version';
    }

    /**
     * Get the latest version for each group.
     *
     * @param $query
     * @param $draftAble bool
     * @param null $testCasesIds
     * @param null $testCasesGroupsIds
     * @return
     */
    public function scopeLastPerGroup(
        $query,
        $draftAble = true,
        $testCasesIds = null,
        $testCasesGroupsIds = null
    ) {
        return $query
            ->when($testCasesIds, function ($query) use (
                $draftAble,
                $testCasesIds,
                $testCasesGroupsIds
            ) {
                $query
                    ->whereIn('id', function ($query) use (
                        $draftAble,
                        $testCasesIds,
                        $testCasesGroupsIds
                    ) {
                        $query
                            ->from(static::getTable())
                            ->selectRaw('MAX(`id`)')
                            ->whereNotIn(
                                'test_case_group_id',
                                $testCasesGroupsIds
                            )
                            ->groupBy(static::getPositionGroupColumn());
                        if (!$draftAble) {
                            $query->where('draft', false);
                        }
                    })
                    ->orWhereIn('id', $testCasesIds);
            })
            ->when(!$testCasesIds, function ($query) use ($draftAble) {
                $query->whereIn('id', function ($query) use ($draftAble) {
                    $query
                        ->from(static::getTable())
                        ->selectRaw('MAX(`id`)')
                        ->groupBy(static::getPositionGroupColumn());
                    if (!$draftAble) {
                        $query->where('draft', false);
                    }
                });
            });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAvailable($query)
    {
        return $query
            ->where(function ($query) {
                $query
                    ->where('public', true)
                    ->orWhereHas('owner', function ($query) {
                        $query->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        );
                    })
                    ->orWhereHas('groups', function ($query) {
                        $query->whereHas('users', function ($query) {
                            $query->whereKey(
                                auth()
                                    ->user()
                                    ->getAuthIdentifier()
                            );
                        });
                    })
                    ->when(
                        auth()
                            ->user()
                            ->can('viewAnyPrivate', self::class),
                        function ($query) {
                            $query->orWhere('public', false);
                        }
                    );
            })
            ->where('draft', false);
    }

    public function scopeWithVersions(Builder $query, Session $session): Builder
    {
        $components = $session->components->where('pivot.version')->values();

        return $query->when($components->count(), function (
            Builder $query
        ) use ($components) {
            $query->whereDoesntHave('components', function (
                Builder $query
            ) use ($components) {
                $components->each(function (Component $component, $key) use (
                    $query
                ) {
                    $function = $key ? 'orWhere' : 'where';

                    $query->$function(function (Builder $query) use (
                        $component
                    ) {
                        $query
                            ->where('id', $component->id)
                            ->where(
                                'component_versions',
                                'not regexp',
                                "(\"{$component->pivot->version}\")"
                            )
                            ->whereJsonLength('component_versions', '>', 0);
                    });
                });
            });
        });
    }

    /**
     * @return mixed
     */
    public function getLastAvailableVersionAttribute()
    {
        return static::available()
            ->lastPerGroup(false)
            ->where('test_case_group_id', $this->test_case_group_id)
            ->first();
    }

    public function isAvailableToUpdate(?Session $session): bool
    {
        if (
            !$session ||
            ($lastTestCase = $this->last_available_version)->version ==
                $this->version
        ) {
            return false;
        }

        $lastTestCaseComponents = $lastTestCase->components->where(
            'pivot.component_versions'
        );

        return !$session->components
            ->where('pivot.version')
            ->filter(function (Component $component) use (
                $lastTestCaseComponents
            ) {
                if (
                    $testCaseComponent = $lastTestCaseComponents->firstWhere(
                        'id',
                        $component->id
                    )
                ) {
                    return !collect(
                        $testCaseComponent->pivot->component_versions
                    )
                        ->filter(function ($version) use ($component) {
                            return preg_match(
                                "/^{$component->pivot->version}$/",
                                $version
                            );
                        })
                        ->count();
                }

                return false;
            })
            ->count();
    }

    /**
     * @return mixed
     */
    public function getLastVersionAttribute()
    {
        return static::lastPerGroup()
            ->where('test_case_group_id', $this->test_case_group_id)
            ->first();
    }

    /**
     * @param Session $session
     * @return array
     */
    public function simulateTestResults(Session $session)
    {
        $simulatedTestResults = [];
        foreach ($this->testSteps as $testStep) {
            $simulatedTestResultsCollection = (new TestResult())->newCollection(
                $simulatedTestResults ?: [$testStep->testResults()->make()]
            );

            $simulatedTestResults[
                $testStep->id
            ] = $testStep->testResults()->make([
                'request' => $testStep->request->withSubstitutions(
                    $simulatedTestResultsCollection,
                    $session
                ),
                'response' => $testStep->response->withSubstitutions(
                    $simulatedTestResultsCollection,
                    $session
                ),
            ]);
        }

        return $simulatedTestResults;
    }
}
