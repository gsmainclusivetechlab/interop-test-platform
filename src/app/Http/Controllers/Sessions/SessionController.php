<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestRunResource;
use App\Models\Session;
use Inertia\Inertia;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('sessions/index', [
            'sessions' => SessionResource::collection(
                auth()->user()->sessions()
                    ->when(request('q'), function ($query, $q) {
                        return $query->where('name', 'like', "%{$q}%");
                    })
                    ->with([
                        'testCases' => function ($query) {
                            return $query->with(['lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @param Session $session
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
            'testRuns' => TestRunResource::collection(
                $session->testRuns()
                    ->with(['session', 'testCase'])
                    ->completed()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
     */
    public function showChartData(Session $session)
    {
        $this->authorize('view', $session);

        $data = [
            [
                'name' => __('Passed'),
                'data' => []
            ],
            [
                'name' => __('Failed'),
                'data' => []
            ],
        ];

        $rows = $session->testRuns()
            ->completed()
            ->selectRaw('COUNT(IF (total = passed, 1, NULL)) AS pass')
            ->selectRaw('COUNT(IF (total != passed, 1, NULL)) AS fail')
            ->selectRaw('DATE_FORMAT(created_at, "%e %b") as date')
            ->whereRaw('DATE_FORMAT(completed_at, "%e %b") < DATE_ADD(NOW(), INTERVAL -1 MONTH)')
            ->groupByRaw('DATE_FORMAT(created_at, "%e %b")')
            ->orderByRaw('DATE_FORMAT(created_at, "%e %b") ASC')
            ->limit(30)
            ->get()
            ->toArray();

        foreach ($rows as $row) {
            $data[0]['data'][] = [
                'x' => $row['date'],
                'y' => $row['pass'],
            ];

            $data[1]['data'][] = [
                'x' => $row['date'],
                'y' => $row['fail'],
            ];
        }

        return $data;
    }
}
