<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\RequestSchemeValidationTest;
use App\Testing\Tests\ResponseSchemeValidationTest;
use League\OpenAPIValidation\PSR7\{
    CallbackAddress,
    CallbackResponseAddress,
    OperationAddress,
    ResponseAddress,
    SpecFinder,
};
use League\OpenAPIValidation\PSR7\Exception\NoPath;
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

        $testStep = $testResult->testStep;
        $apiSpecCollection = $testStep->apiSpec()
            ->pluck('openapi', 'name');
        if ($apiSpec = $apiSpecCollection->first()) {
            $specFinder = new SpecFinder($apiSpec);

            $testSuite->addTest(
                new RequestSchemeValidationTest(
                    $testResult->request,
                    $apiSpecCollection->keys()->first(),
                    $testStep->isCallback() ?
                        new CallbackAddress(
                            $testStep->callback_origin_path,
                            strtolower($testStep->callback_origin_method),
                            $testStep->callback_name,
                            strtolower($testStep->method)
                        ) :
                        new OperationAddress(
                            $testStep->path,
                            strtolower($testStep->method)
                        ),
                    $specFinder
                )
            );
            $testSuite->addTest(
                new ResponseSchemeValidationTest(
                    $testResult->response,
                    $apiSpecCollection->keys()->first(),
                    $testStep->isCallback() ?
                        new CallbackResponseAddress(
                            $testStep->callback_origin_path,
                            strtolower($testStep->callback_origin_method),
                            $testStep->callback_name,
                            strtolower($testStep->method),
                            $testResult->response->status()
                        ) :
                        new ResponseAddress(
                            $testStep->path,
                            strtolower($testStep->method),
                            $testResult->response->status()
                        ),
                    $specFinder
                )
            );
        }

        return $testSuite;
    }
}
