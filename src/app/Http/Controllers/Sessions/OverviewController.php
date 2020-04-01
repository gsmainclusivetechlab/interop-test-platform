<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\View\Components\Charts\LatestTestRuns;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->when(request('q'), function (Builder $query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
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
        $useCases = $session->testCases->mapWithKeys(function ($item) {
            return [$item->useCase];
        });

        return view('sessions.show', compact('session', 'testRuns', 'useCases'));
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
        return (new LatestTestRuns($session))->toArray();
    }
}
