<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\TestCase;

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
//        dd(route('sessions.test-cases.test-data.destroy', [Session::first(), TestCase::first()]));
        $sessions = auth()->user()->sessions()
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
