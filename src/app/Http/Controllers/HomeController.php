<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;

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
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('home', [

        ]);
//        $sessions = auth()->user()->sessions()
//            ->with(['testCases', 'lastTestRun'])
//            ->latest()
//            ->paginate(12);
//
//        return view('home', compact('sessions'));
    }
}
