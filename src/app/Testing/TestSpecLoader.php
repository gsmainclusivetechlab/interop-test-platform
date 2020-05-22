<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\RequestSchemeValidationTest;
use App\Testing\Tests\ResponseSchemeValidationTest;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseAddress;
use PHPUnit\Framework\TestSuite;

class TestSpecLoader
{
    /**
     * @param TestResult $testResult
     * @return TestSuite
     * @throws NoPath
     */
    public function load(TestResult $testResult)
    {
        $testSuite = new TestSuite();

        if ($apiSpec = $testResult->testStep->apiSpec) {
            $testSuite->addTest(new RequestSchemeValidationTest(
                $testResult->request,
                $apiSpec,
                new OperationAddress($testResult->testStep->path, strtolower($testResult->testStep->method))
            ));
            $testSuite->addTest(new ResponseSchemeValidationTest(
                $testResult->response,
                $apiSpec,
                new ResponseAddress($testResult->testStep->path, strtolower($testResult->testStep->method), $testResult->response->status())
            ));
        }

        return $testSuite;
    }
}
