<?php

namespace App\Http\Controllers\Settings;

use App\Models\TestSession;
use App\Http\Controllers\Controller;

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
        $sessions = TestSession::with('user')->paginate();

        return view('settings.sessions.index', compact('sessions'));
    }
}
