<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\TestCase;

class TestStepsController extends Controller
{
    /**
     * TestDatumController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Session $session, TestCase $testCase)
    {
        $testSteps = $testCase->testSteps;

        return view('sessions.test-steps.index', compact('session', 'testCase', 'testSteps'));
    }
}