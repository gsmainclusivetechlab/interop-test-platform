<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestSession;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->withoutTrashed()
            ->when(request('q'), function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function trash()
    {
        $sessions = auth()->user()->sessions()
            ->onlyTrashed()
            ->when(request('q'), function ($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestSession $session)
    {
        $this->authorize('view', $session);
        $runs = $session->runs()
            ->with('case', 'session')
            ->latest()
            ->paginate();
        $suites = $session->cases->mapWithKeys(function ($item) {
            return [$item->suite];
        });

        return view('sessions.show', compact('session', 'runs', 'suites'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(TestSession $session)
    {
        $this->authorize('delete', $session);
        $session->delete();

        return redirect()
            ->back()
            ->with('success', __('Session deactivated successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(int $id)
    {
        $session = TestSession::onlyTrashed()
            ->findOrFail($id);
        $this->authorize('restore', $session);
        $session->restore();

        return redirect()
            ->back()
            ->with('success', __('Session activated successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceDestroy(int $id)
    {
        $session = TestSession::withTrashed()
            ->findOrFail($id);
        $this->authorize('delete', $session);
        $session->forceDelete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }

    /**
     * @param TestSession $session
     * @return array
     */
    public function showChart(TestSession $session)
    {
        $data = [
            "data1" => [220, 240, 270, 250, 280],
            "data2" => [180, 150, 300, 70, 120],
            "data3" => [200, 310, 150, 100, 180],
        ];
        return $data;
    }
}
