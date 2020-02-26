<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\TestSession;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestSession::class, 'session');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sessions = TestSession::whereHas('owner', function ($query) {
                $query->when(request('q'), function ($query, $q) {
                    $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate();

        return view('admin.sessions.index', compact('sessions'));
    }
}
