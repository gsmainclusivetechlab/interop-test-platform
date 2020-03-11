<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class SessionController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Session::class, 'session');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = Session::whereHas('owner', function (Builder $query) {
                $query->when(request('q'), function (Builder $query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->when(request()->route()->hasParameter('trashed'), function (Builder $query, $trashed) {
                return $trashed ? $query->onlyTrashed() : $query->withoutTrashed();
            })
            ->latest()
            ->paginate();

        return view('admin.sessions.index', compact('sessions'));
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
}
