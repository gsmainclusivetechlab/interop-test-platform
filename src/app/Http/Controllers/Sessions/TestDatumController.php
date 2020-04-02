<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreTestDatumRequest;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestDatum;

class TestDatumController extends Controller
{
    /**
     * TestDatumController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Session $session, TestCase $testCase)
    {
        return view('sessions.test-data.index', compact('session', 'testCase'));
    }

    public function create(Session $session, TestCase $testCase)
    {
        return view('sessions.test-data.create', compact('session', 'testCase'));
    }

    public function store(Session $session, TestCase $testCase, StoreTestDatumRequest $request)
    {
        dd($session);
    }

    public function show(Session $session, TestCase $testCase)
    {
        dd($testCase);
    }
}
