<?php declare(strict_types=1);

namespace App\Http\Controllers;

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
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
