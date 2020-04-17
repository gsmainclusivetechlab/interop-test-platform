<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestRunResource;
use App\Models\Session;
use App\View\Components\Sessions\LatestTestRunsChart;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class OverviewController extends Controller
{
    /**
     * OverviewController constructor.
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
                    ->when(request('q'), function (Builder $query, $q) {
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
            'session' => (new SessionResource($session))->resolve(),
            'testRuns' => TestRunResource::collection(
                $session->testRuns()
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
        $session->delete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }

    /**
     * @param Session $session
     * @return array
     */
    public function showChartData(Session $session)
    {
        return (new LatestTestRunsChart($session))->toArray();
    }
}
