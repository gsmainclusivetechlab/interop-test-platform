<?php

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
        $test = new \App\Testing\TestCase('execute');
        $runner = new \PHPUnit\TextUI\TestRunner();
        $result = $runner->doRun($test, [
            'warnings' => [],
        ]);

        dd(1);

        return view('home');
    }
}
