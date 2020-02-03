<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\TestSession;

class CaseController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param TestSession $session
     * @param TestCase $case
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestSession $session, TestCase $case)
    {
        return view('sessions.cases.show', compact('session', 'case'));
    }
}
