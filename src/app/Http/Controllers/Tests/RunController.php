<?php

namespace App\Http\Controllers\Tests;

use App\Http\Controllers\Controller;
use App\Testing\ResultPrinter;
use App\Testing\TestCase;
use App\Testing\TestRunner;

class RunController extends Controller
{
    public function handle()
    {
        $test = new TestCase('execute');
//        $runner = new \PHPUnit\TextUI\TestRunner();
//        $runner->setPrinter(new ResultPrinter());
//        $result = $runner->doRun($test, [
//            'warnings' => [],
//        ]);

        dd(1);

        return view('home');
    }
}
