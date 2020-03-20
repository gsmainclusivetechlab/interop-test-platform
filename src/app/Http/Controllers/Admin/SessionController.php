<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
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
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate();

        return view('admin.sessions.index', compact('sessions'));
    }
}
