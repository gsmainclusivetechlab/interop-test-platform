<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->when(request('q'), function (Builder $query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->when(request()->route()->hasParameter('trashed'), function (Builder $query, $trashed) {
                return $trashed ? $query->onlyTrashed() : $query->withoutTrashed();
            })
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
        $runs = $session->testRuns()
            ->with('case', 'session')
            ->latest()
            ->paginate();
        $suites = $session->cases->mapWithKeys(function ($item) {
            return [$item->suite];
        });

        return view('sessions.show', compact('session', 'runs', 'suites'));
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
            ->with('success', __('Session deactivated successfully'));
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function restore(int $id)
    {
        $session = Session::onlyTrashed()
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
        $session = Session::withTrashed()
            ->findOrFail($id);
        $this->authorize('delete', $session);
        $session->forceDelete();

        return redirect()
            ->back()
            ->with('success', __('Session deleted successfully'));
    }

//    /**
//     * @param Session $session
//     * @return array
//     */
//    public function showChart(Session $session)
//    {
//        $data = [
//            'x' => ['2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05', '2013-01-06'],
//            [220, 240, 270, 250, 280],
//            [180, 150, 300, 70, 120],
//        ];
//        return $data;
//    }
}
