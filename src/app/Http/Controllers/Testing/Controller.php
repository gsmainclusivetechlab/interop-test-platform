<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestResult;
use App\Testing\Listeners\TestExecutionListener;
use App\Testing\TestRunner;
use App\Testing\TestSuiteLoader;

class Controller extends BaseController
{
    /**
     * @param TestResult $testResult
     * @return \PHPUnit\Framework\TestResult
     */
    protected function doTest(TestResult $testResult)
    {
        $loader = new TestSuiteLoader();
        $runner = new TestRunner();
        $runner->addListener(new TestExecutionListener($testResult));
        return $runner->run($loader->load($testResult));
    }
}
