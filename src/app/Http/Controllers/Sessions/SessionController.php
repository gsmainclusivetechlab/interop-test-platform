<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Enums\AuditActionEnum;
use App\Enums\AuditTypeEnum;
use App\Http\Exports\SessionExportFactory;
use PhpOffice\PhpWord\Settings as PhpWordSettings;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sessions\Traits\WithSutUrls;
use App\Http\Exports\ComplianceSessionExport;
use App\Http\Requests\SessionRequest;
use App\Notifications\SessionStatusChanged;
use App\Utils\AuditLogUtil;
use Auth;
use App\Http\Resources\{
    ComponentResource,
    SectionResource,
    SessionResource,
    SimulatorPluginResource,
    TestRunResource,
    UseCaseResource
};
use App\Models\{
    Certificate,
    Component,
    FileEnvironment,
    GroupEnvironment,
    QuestionnaireSection,
    Session,
    TestCase,
    TestRun,
    UseCase,
    User
};
use Arr;
use Exception;
use File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use PhpOffice\PhpWord\IOFactory;
use Throwable;

class SessionController extends Controller
{
    use WithSutUrls;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('sessions/index', [
            'sessions' => SessionResource::collection(
                Session::whereHas('owner', function (Builder $query) {
                    $query
                        ->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        )
                        ->orWhereHas('groups', function (Builder $query) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        });
                })
                    ->when(request('q'), function ($query, $q) {
                        return $query->where('name', 'like', "%{$q}%");
                    })
                    ->with(['owner', 'lastTestRun'])
                    ->latest()
                    ->paginate()
            ),
            'sessionsCount' => Session::whereHas('owner', function (
                Builder $query
            ) {
                $query
                    ->whereKey(
                        auth()
                            ->user()
                            ->getAuthIdentifier()
                    )
                    ->orWhereHas('groups', function (Builder $query) {
                        $query->whereHas('users', function (Builder $query) {
                            $query->whereKey(
                                auth()
                                    ->user()
                                    ->getAuthIdentifier()
                            );
                        });
                    });
            })->count(),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Session $session)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/show', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) use ($session) {
                        return $query->with([
                            'useCase',
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ]);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'questionnaire' => SectionResource::collection(
                QuestionnaireSection::getSessionQuestionnaire($session)
            ),
            'useCases' => UseCaseResource::collection(
                UseCase::withTestCasesOfSession($session)->get()
            ),
            'testRuns' => TestRunResource::collection(
                $session
                    ->testRuns()
                    ->with(['session', 'testCase'])
                    ->latest()
                    ->paginate()
            ),
            'sutUrls' => $this->getSutUrls($session),
        ]);
    }

    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Session $session)
    {
        $this->authorize('update', $session);
        $session->load([
            'testCases' => function ($query) use ($session) {
                return $query->with([
                    'useCase',
                    'lastTestRun' => function ($query) use ($session) {
                        $query->where('session_id', $session->id);
                    },
                ]);
            },
            'groupEnvironment',
            'fileEnvironments',
            'simulatorPlugin',
        ]);
        $sessionTestCasesIds = $session->testCases->pluck('id');
        $sessionTestCasesGroupIds = $session->testCases->pluck(
            'test_case_group_id'
        );

        return Inertia::render('sessions/edit', [
            'session' => (new SessionResource($session))->resolve(),
            'components' => ComponentResource::collection($session->components),
            'hasGroupCertificates' => Certificate::hasGroupCertificates(),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use (
                        $session,
                        $sessionTestCasesIds,
                        $sessionTestCasesGroupIds
                    ) {
                        $query
                            ->where(function ($query) use (
                                $session,
                                $sessionTestCasesIds,
                                $sessionTestCasesGroupIds
                            ) {
                                $query
                                    ->withVersions($session)
                                    ->available()
                                    ->lastPerGroup(
                                        false,
                                        $sessionTestCasesIds,
                                        $sessionTestCasesGroupIds
                                    );
                            })
                            ->when($session->isComplianceSession(), function (
                                $query
                            ) use ($session) {
                                $query->whereIn(
                                    'id',
                                    $session->testCases()->pluck('id')
                                );
                            })
                            ->orderBy('name');
                    },
                ])
                    ->whereHas('testCases', function ($query) use ($session) {
                        $query
                            ->when(
                                !auth()
                                    ->user()
                                    ->can('viewAny', TestCase::class),
                                function ($query) {
                                    $query->where('public', true);
                                }
                            )
                            ->withVersions($session);
                    })
                    ->get()
            ),
            'hasGroupEnvironments' => GroupEnvironment::whereHas(
                'group',
                function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        );
                    });
                }
            )->exists(),
            'simulatorPlugins' => SimulatorPluginResource::collection(
                Auth::user()
                    ->groups->pluck('simulatorPlugins')
                    ->flatten()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function report(Session $session)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/report', [
            'session' => (new SessionResource($session))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::WithTestCasesAndTestRunsOfSession($session)->get()
            ),
        ]);
    }

    public function downloadPdf(Session $session, Request $request)
    {
        $this->authorize('view', $session);
        $data = $request->validate([
            'type_of_report' => ['required', Rule::in(['simple', 'extended'])],
            'test_runs' => 'required',
            'test_runs.*' => [
                'required',
                Rule::exists(TestRun::class, 'id')->where(function (
                    $query
                ) use ($session) {
                    return $query->where('session_id', $session->id);
                }),
            ],
        ]);

        try {
            PhpWordSettings::setPdfRendererPath(base_path('/vendor/mpdf/mpdf'));
            PhpWordSettings::setPdfRendererName('MPDF');

            $name = "Session-{$session->id}-{$session->name}-Report-{$data['type_of_report']}";
            $path = storage_path("framework/docs/{$name}.pdf");
            $wordFile = SessionExportFactory::resolveSessionExport(
                $session
            )->exportPdf($session, $data);
            $objWriter = IOFactory::createWriter($wordFile, 'PDF');
            $objWriter->save($path);

            app()->terminating(function () use ($path) {
                File::delete($path);
            });

            new AuditLogUtil(
                $request,
                AuditActionEnum::SESSION_REPORT_CREATED(),
                AuditTypeEnum::SESSION_TYPE,
                $session->id,
                $request->toArray()
            );

            return response()->download($path);
        } catch (Throwable $e) {
            dd($e);
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Session $session
     * @param TestCase $testCaseToRemove
     * @param TestCase $testCaseToAdd
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updateTestCase(
        Session $session,
        TestCase $testCaseToRemove,
        TestCase $testCaseToAdd
    ) {
        $this->authorize('update', $session);

        $testCaseToRemove->isAvailableToUpdate($session);

        abort_unless(
            $testCaseToRemove->isAvailableToUpdate($session),
            403,
            __(
                'New version of test case component mismatch with session SUT version'
            )
        );

        try {
            DB::transaction(function () use (
                $session,
                $testCaseToRemove,
                $testCaseToAdd
            ) {
                $session
                    ->testCasesWithSoftDeletes()
                    ->whereKey($testCaseToRemove)
                    ->whereHas('testRunsWithSoftDeletesTestCases', function (
                        $query
                    ) use ($session) {
                        $query->where('session_id', $session->getKey());
                    })
                    ->each(function ($testCase) {
                        $testCase->pivot->update([
                            'deleted_at' => $testCase->fromDateTime(
                                $testCase->freshTimestamp()
                            ),
                        ]);
                    });

                $session
                    ->testCasesWithSoftDeletes()
                    ->whereKey($testCaseToRemove)
                    ->whereDoesntHave(
                        'testRunsWithSoftDeletesTestCases',
                        function ($query) use ($session) {
                            $query->where('session_id', $session->getKey());
                        }
                    )
                    ->each(function ($testCase) use ($session) {
                        $testCase->pivot->delete();
                    });

                $session->testCasesWithSoftDeletes()->attach($testCaseToAdd);
            });

            return redirect()
                ->route('sessions.edit', $session)
                ->with('success', __('Test Case updated successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Session $session
     * @param SessionRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function update(Session $session, SessionRequest $request)
    {
        $this->authorize('update', $session);

        try {
            $session = DB::transaction(function () use ($session, $request) {
                $data = array_merge(
                    [
                        'environments' => [],
                    ],
                    $request->validated()
                );

                $session->update(
                    $session->isComplianceSession()
                        ? Arr::only($data, [
                            'group_environment_id',
                            'environments',
                        ])
                        : $data
                );

                FileEnvironment::syncEnvironments(
                    $session,
                    Arr::get($request->all(), 'fileEnvironments')
                );

                $session->components->each(function (Component $component) use (
                    $data,
                    $session,
                    $request
                ) {
                    $component->pivot->update(
                        $data['components'][$component->id]
                    );

                    if (
                        $component->pivot->use_encryption &&
                        !$component->pivot->implicitSut &&
                        (!$component->pivot->certificate ||
                            !$component->pivot->certificate->certificable_id)
                    ) {
                        $certificate = $component->pivot
                            ->certificate()
                            ->updateOrCreate(
                                [],
                                Certificate::getCertificateAttribures(
                                    $request,
                                    $component->pivot->certificate,
                                    "certificates.{$component->id}.ca_crt",
                                    "certificates.{$component->id}.client_crt"
                                )
                            );

                        $component->pivot->update([
                            'certificate_id' => $certificate->id,
                        ]);
                    }
                });

                if (!$session->isComplianceSession()) {
                    $session
                        ->testCasesWithSoftDeletes()
                        ->whereKey($request->input('test_cases'))
                        ->each(function ($testCase) {
                            $testCase->pivot->update(['deleted_at' => null]);
                        });

                    $session
                        ->testCasesWithSoftDeletes()
                        ->whereKeyNot($request->input('test_cases'))
                        ->whereHas(
                            'testRunsWithSoftDeletesTestCases',
                            function ($query) use ($session) {
                                $query->where('session_id', $session->getKey());
                            }
                        )
                        ->each(function ($testCase) {
                            $testCase->pivot->update([
                                'deleted_at' => $testCase->fromDateTime(
                                    $testCase->freshTimestamp()
                                ),
                            ]);
                        });

                    $session
                        ->testCasesWithSoftDeletes()
                        ->whereKeyNot($request->input('test_cases'))
                        ->whereDoesntHave(
                            'testRunsWithSoftDeletesTestCases',
                            function ($query) use ($session) {
                                $query->where('session_id', $session->getKey());
                            }
                        )
                        ->each(function ($testCase) use ($session) {
                            $testCase->pivot->delete();
                        });

                    $session->testCasesWithSoftDeletes()->attach(
                        collect($request->input('test_cases'))
                            ->diff(
                                $session
                                    ->testCasesWithSoftDeletes()
                                    ->pluck('id')
                            )
                            ->all()
                    );
                }

                return $session;
            });
            new AuditLogUtil(
                $request,
                AuditActionEnum::SESSION_EDITED(),
                AuditTypeEnum::SESSION_TYPE,
                $session->id,
                $request->toArray()
            );

            return redirect()
                ->route('sessions.show', $session)
                ->with('success', __('Session updated successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Session $session
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function complete(Session $session)
    {
        $this->authorize('update', $session);

        if ($session->completable) {
            $session->updateStatus(
                Session::STATUS_IN_VERIFICATION,
                'completed_at'
            );

            User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_SUPERADMIN,
            ])->each(function (User $user) use ($session) {
                $user->notify(new SessionStatusChanged($session));
            });

            return redirect()
                ->back()
                ->with('success', __('Session has been sent for verification'));
        }

        return redirect()
            ->back()
            ->with('error', __('Session not completable'));
    }

    /**
     * @param Session $session
     *
     * @throws AuthorizationException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function export(Session $session)
    {
        $this->authorize('view', $session);

        $session->load([
            'testCases.testRuns' => function (HasMany $query) use ($session) {
                $query->where('session_id', $session->id);
            },
        ]);

        $wordFile = app(ComplianceSessionExport::class)->export($session);

        $fileName = "Session-{$session->id}-{$session->name}";
        $path = storage_path("framework/docs/{$fileName}.docx");

        $objWriter = IOFactory::createWriter($wordFile);
        $objWriter->save($path);

        app()->terminating(function () use ($path) {
            File::delete($path);
        });

        return response()->download($path);
    }

    /**
     * @param Session $session
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Session $session)
    {
        $this->authorize('delete', $session);
        $session->delete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }

    /**
     * @param Session $session
     * @return array[]
     * @throws AuthorizationException
     */
    public function showChartData(Session $session)
    {
        $this->authorize('view', $session);

        $data = [
            [
                'name' => __('Passed'),
                'data' => [],
            ],
            [
                'name' => __('Failed'),
                'data' => [],
            ],
        ];

        $rows = $session
            ->testRuns()
            ->completed()
            ->selectRaw('COUNT(IF (total = passed, 1, NULL)) AS pass')
            ->selectRaw('COUNT(IF (total != passed, 1, NULL)) AS fail')
            ->selectRaw('DATE_FORMAT(created_at, "%c %d %b") as date')
            ->whereRaw(
                'DATE_FORMAT(completed_at, "%c %e %b") < DATE_ADD(NOW(), INTERVAL -1 MONTH)'
            )
            ->groupByRaw('DATE_FORMAT(created_at, "%c %d %b")')
            ->orderByRaw('DATE_FORMAT(created_at, "%c %d %b") ASC')
            ->limit(30)
            ->get()
            ->toArray();

        foreach ($rows as $row) {
            $data[0]['data'][] = [
                'x' => substr($row['date'], 2, 6),
                'y' => $row['pass'],
            ];

            $data[1]['data'][] = [
                'x' => substr($row['date'], 2, 6),
                'y' => $row['fail'],
            ];
        }

        return $data;
    }
}
