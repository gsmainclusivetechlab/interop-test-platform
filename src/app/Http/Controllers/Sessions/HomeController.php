<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;

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
        $sessions = auth()->user()->sessions()->when(request('q'), function ($query, $q) {
            return $query->where('name', 'like', "%{$q}%");
        })->paginate();

        return view('sessions.index', compact('sessions'));
    }
}
