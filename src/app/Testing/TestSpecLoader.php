<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\RequestBodyParamsValidationTest;
use App\Testing\Tests\RequestHeaderParamsValidationTest;
use App\Testing\Tests\RequestPathParamsValidationTest;
use App\Testing\Tests\RequestQueryParamsValidationTest;
use App\Testing\Tests\ResponseBodyParamsValidationTest;
use App\Testing\Tests\ResponseHeaderParamsValidationTest;
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

        if ($apiSpec = $testResult->testStep->apiSpec) {
            $operationAddress = new OperationAddress($testResult->testStep->path, strtolower($testResult->testStep->method));
            $responseOperationAddress = new ResponseAddress($testResult->testStep->path, strtolower($testResult->testStep->method), $testResult->response->status());
            $specFinder = new SpecFinder($apiSpec->openapi);

            if ($requestHeaderSpecs = $specFinder->findHeaderSpecs($operationAddress)) {
                $testSuite->addTest(new RequestHeaderParamsValidationTest($testResult->request, $apiSpec, $operationAddress, $requestHeaderSpecs));
            }

            if ($requestPathSpecs = $specFinder->findPathSpecs($operationAddress)) {
                $testSuite->addTest(new RequestPathParamsValidationTest($testResult->request, $apiSpec, $operationAddress, $requestPathSpecs));
            }

            if ($requestQuerySpecs = $specFinder->findQuerySpecs($operationAddress)) {
                $testSuite->addTest(new RequestQueryParamsValidationTest($testResult->request, $apiSpec, $operationAddress, $requestQuerySpecs));
            }

            if ($requestBodySpec = $specFinder->findBodySpec($operationAddress)) {
                $testSuite->addTest(new RequestBodyParamsValidationTest($testResult->request, $apiSpec, $operationAddress, $requestBodySpec));
            }

            if ($responseHeaderSpecs = $specFinder->findHeaderSpecs($responseOperationAddress)) {
                $testSuite->addTest(new ResponseHeaderParamsValidationTest($testResult->response, $apiSpec, $responseOperationAddress, $responseHeaderSpecs));
            }

            if ($responseBodySpec = $specFinder->findBodySpec($responseOperationAddress)) {
                $testSuite->addTest(new ResponseBodyParamsValidationTest($testResult->response, $apiSpec, $responseOperationAddress, $responseBodySpec));
            }
        }

        return $testSuite;
    }
}
