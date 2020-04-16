<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
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
                    ->with(['testCases', 'lastTestRun'])
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Session $session)
    {
        $this->authorize('view', $session);
        $testRuns = $session->testRuns()
            ->latest()
            ->paginate();

        return view('sessions.show', compact('session', 'testRuns'));
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
