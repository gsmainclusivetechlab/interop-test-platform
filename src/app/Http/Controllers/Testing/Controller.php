<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Middleware\ValidateTraceContext;
use App\Http\Middleware\SetJsonHeaders;

class Controller extends BaseController
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['api', /*ValidateTraceContext::class,*/ SetJsonHeaders::class]);
    }

    protected function createTestRunner()
    {
//        $testSuite = new TestSuite();
//        $testSuite->addTest(new RequestTest(request()));
//
//        $testRunner = new TestRunner();
//        $testResult = $testRunner->run($testSuite);
//
//        dd($testResult);
    }
}
