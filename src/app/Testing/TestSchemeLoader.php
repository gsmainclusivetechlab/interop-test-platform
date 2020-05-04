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
use League\OpenAPIValidation\PSR7\PathFinder;
use League\OpenAPIValidation\PSR7\ResponseAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use PHPUnit\Framework\TestSuite;

class TestSchemeLoader
{
    /**
     * @param TestResult $testResult
     * @return TestSuite
     * @throws NoPath
     */
    public function load(TestResult $testResult)
    {
        $testSuite = new TestSuite();

        if ($apiScheme = $testResult->testStep->apiScheme) {
            $request = $testResult->request->toPsrRequest();
            $pathFinder = new PathFinder($apiScheme->openapi, $request->getUri(), $request->getMethod());

            if ($operationAddress = collect($pathFinder->search())->first()) {
                $specFinder = new SpecFinder($apiScheme->openapi);

                if ($requestHeaderSpecs = $specFinder->findHeaderSpecs($operationAddress)) {
                    $testSuite->addTest(new RequestHeaderParamsValidationTest($testResult->request, $apiScheme, $operationAddress, $requestHeaderSpecs));
                }

                if ($requestPathSpecs = $specFinder->findPathSpecs($operationAddress)) {
                    $testSuite->addTest(new RequestPathParamsValidationTest($testResult->request, $apiScheme, $operationAddress, $requestPathSpecs));
                }

                if ($requestQuerySpecs = $specFinder->findQuerySpecs($operationAddress)) {
                    $testSuite->addTest(new RequestQueryParamsValidationTest($testResult->request, $apiScheme, $operationAddress, $requestQuerySpecs));
                }

                if ($requestBodySpec = $specFinder->findBodySpec($operationAddress)) {
                    $testSuite->addTest(new RequestBodyParamsValidationTest($testResult->request, $apiScheme, $operationAddress, $requestBodySpec));
                }

                $responseOperationAddress = new ResponseAddress($operationAddress->path(), $operationAddress->method(), $testResult->response->status());

                if ($responseHeaderSpecs = $specFinder->findHeaderSpecs($responseOperationAddress)) {
                    $testSuite->addTest(new ResponseHeaderParamsValidationTest($testResult->response, $apiScheme, $responseOperationAddress, $responseHeaderSpecs));
                }

                if ($responseBodySpec = $specFinder->findBodySpec($responseOperationAddress)) {
                    $testSuite->addTest(new ResponseBodyParamsValidationTest($testResult->response, $apiScheme, $responseOperationAddress, $responseBodySpec));
                }
            }
        }

        return $testSuite;
    }
}
