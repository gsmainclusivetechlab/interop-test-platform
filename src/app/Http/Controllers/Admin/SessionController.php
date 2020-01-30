<?php

namespace App\Http\Controllers\Admin;

use App\Models\TestSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    /**
     * UserController constructor.
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
        $sessions = TestSession::whereHas('user', function ($query) {
            $query->when(request('q'), function ($query, $q) {
                return $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        })->withCount(['cases'])->latest()->paginate();

        return view('admin.sessions.index', compact('sessions'));
    }
}
