<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestResult;
use App\Testing\Extensions\TestExecutionExtension;
use App\Testing\TestRunner;
use App\Testing\TestSuiteLoader;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ResponseInterface;

class Controller extends BaseController
{
    /**
     * @param TestResult $testResult
     * @return ResponseInterface
     */
    protected function doTest(TestResult $testResult)
    {
        $suite = new TestSuite();
        $loader = new TestSuiteLoader($testResult->testStep);
        $suite->addTestSuite($loader->loadRequestTests($testResult->request));
        $suite->addTestSuite($loader->loadResponseTests($testResult->response));
        $runner = new TestRunner();
        $runner->addExtension(new TestExecutionExtension($testResult));
        $result = $runner->run($suite);

        if ($result->wasSuccessful()) {
            $testResult->passed();
        } else {
            $testResult->failure();
        }

        return $testResult->response;
    }
}
