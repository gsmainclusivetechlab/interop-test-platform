<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\TestCase;

class TestDataExampleController extends Controller
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
        return view('sessions.test-data-examples.index', compact('session', 'testCase'));
    }
}