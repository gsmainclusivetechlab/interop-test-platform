<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestRunResource;
use App\Http\Resources\UseCaseResource;
use App\Models\GroupEnvironment;
use App\Models\Session;
use App\Models\UseCase;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class SessionController extends Controller
{
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
                        ->whereKey(auth()->user())
                        ->orWhereHas('groups', function (Builder $query) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(auth()->user());
                            });
                        });
                })
                    ->when(request('q'), function ($query, $q) {
                        return $query->where('name', 'like', "%{$q}%");
                    })
                    ->with([
                        'owner',
                        'testCases' => function ($query) {
                            return $query->with(['useCase', 'lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->paginate()
            ),
            'sessionsCount' => Session::whereHas('owner', function (
                Builder $query
            ) {
                $query
                    ->whereKey(auth()->user())
                    ->orWhereHas('groups', function (Builder $query) {
                        $query->whereHas('users', function (Builder $query) {
                            $query->whereKey(auth()->user());
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
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                ])
            ))->resolve(),
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

        return Inertia::render('sessions/edit', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                ])
            ))->resolve(),
            'component' => (new ComponentResource(
                $session->components()->firstOrFail(),
            ))->resolve(),
            'hasGroupEnvironments' => GroupEnvironment::whereHas('group', function (Builder $query) {
                $query->whereHas('users', function (Builder $query) {
                    $query->whereKey(auth()->user());
                });
            })->exists(),
        ]);
    }

    /**
     * @param Session $session
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function update(Session $session, Request $request)
    {
        $this->authorize('update', $session);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'environments.*.name' => ['required'],
            'environments.*.value' => ['required'],
            'component_id' => ['required', 'exists:components,id'],
            'component_base_url' => ['required', 'url', 'max:255'],
        ]);

        try {
            $session = DB::transaction(function () use ($session, $request) {
                $session->update($request->input());
                $session->components()
                    ->updateExistingPivot($request->input('component_id'), [
                        'base_url' => $request->input('component_base_url'),
                    ]);

                return $session;
            });

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
