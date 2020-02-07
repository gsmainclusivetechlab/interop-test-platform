<?php

namespace App\Http\Controllers;

use App\Testing\RequestTest;
use App\Testing\TestRunner;
use PHPUnit\Framework\TestSuite;

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
//        $testSuite = new TestSuite();
//        $testSuite->addTest(new RequestTest(request()));
//
//        $testRunner = new TestRunner();
//        $testResult = $testRunner->run($testSuite);
//
//        dd($testResult);

        return view('home');
    }
}
