<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\RequestSchemeValidationTest;
use App\Testing\Tests\ResponseSchemeValidationTest;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
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

        $apiSpecCollection = $testResult->testStep
            ->apiSpec()
            ->pluck('openapi', 'name');
        if ($apiSpec = $apiSpecCollection->first()) {
            $specFinder = new SpecFinder($apiSpec);

            $testSuite->addTest(
                new RequestSchemeValidationTest(
                    $testResult->request,
                    $apiSpecCollection->keys()->first(),
                    new OperationAddress(
                        $testResult->testStep->path,
                        strtolower($testResult->testStep->method)
                    ),
                    $specFinder
                )
            );
            $testSuite->addTest(
                new ResponseSchemeValidationTest(
                    $testResult->response,
                    $apiSpecCollection->keys()->first(),
                    new ResponseAddress(
                        $testResult->testStep->path,
                        strtolower($testResult->testStep->method),
                        $testResult->response->status()
                    ),
                    $specFinder
                )
            );
        }

        return $testSuite;
    }
}
