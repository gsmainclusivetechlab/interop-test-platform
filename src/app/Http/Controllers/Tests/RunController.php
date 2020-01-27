<?php

namespace App\Http\Controllers\Tests;

use App\Http\Controllers\Controller;

class RunController extends Controller
{
    public function handle()
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
