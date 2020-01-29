<?php

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
        $this->middleware('can:viewAnyOwn,' . TestSession::class)->only('index');
        // $this->authorizeResource(TestSession::class, 'session');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()->when(request('q'), function ($query, $q) {
            return $query->where('name', 'like', "%{$q}%");
        })->paginate();

        return view('sessions.index', compact('sessions'));
    }

    /**
     * @param TestSession $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestSession $session)
    {
        return view('sessions.show', compact('session'));
    }
}
