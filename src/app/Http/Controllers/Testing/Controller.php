<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestResult;
use App\Testing\Extensions\TestExecutionExtension;
use App\Testing\Extensions\TestResultExtension;
use App\Testing\TestRunner;
use App\Testing\TestSuiteLoader;
use Psr\Http\Message\ResponseInterface;

class Controller extends BaseController
{
    /**
     * @param TestResult $testResult
     * @return ResponseInterface
     */
    protected function doTest(TestResult $testResult)
    {
        $loader = new TestSuiteLoader();
        $runner = new TestRunner();
        $runner->addExtension(new TestResultExtension($testResult));
        $runner->addExtension(new TestExecutionExtension($testResult));
        $result = $runner->run($loader->load($testResult));

//        if ($result->wasSuccessful()) {
//            $testResult->passed();
//        } else {
//            $testResult->failure();
//        }

        return $testResult->response->toResponse();
    }
}
