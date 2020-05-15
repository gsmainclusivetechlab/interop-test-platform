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

        if ($connection = $testResult->testStep->source->connections()->whereKey($testResult->testStep->target->getKey())->first()) {
            $specification = $connection->pivot->specification;

            $operationAddress = new OperationAddress($testResult->testStep->path, strtolower($testResult->testStep->method));
            $specFinder = new SpecFinder($specification->openapi);

            if ($requestHeaderSpecs = $specFinder->findHeaderSpecs($operationAddress)) {
                $testSuite->addTest(new RequestHeaderParamsValidationTest($testResult->request, $specification, $operationAddress, $requestHeaderSpecs));
            }

            if ($requestPathSpecs = $specFinder->findPathSpecs($operationAddress)) {
                $testSuite->addTest(new RequestPathParamsValidationTest($testResult->request, $specification, $operationAddress, $requestPathSpecs));
            }

            if ($requestQuerySpecs = $specFinder->findQuerySpecs($operationAddress)) {
                $testSuite->addTest(new RequestQueryParamsValidationTest($testResult->request, $specification, $operationAddress, $requestQuerySpecs));
            }

            if ($requestBodySpec = $specFinder->findBodySpec($operationAddress)) {
                $testSuite->addTest(new RequestBodyParamsValidationTest($testResult->request, $specification, $operationAddress, $requestBodySpec));
            }

            $responseOperationAddress = new ResponseAddress($operationAddress->path(), $operationAddress->method(), $testResult->response->status());

            if ($responseHeaderSpecs = $specFinder->findHeaderSpecs($responseOperationAddress)) {
                $testSuite->addTest(new ResponseHeaderParamsValidationTest($testResult->response, $specification, $responseOperationAddress, $responseHeaderSpecs));
            }

            if ($responseBodySpec = $specFinder->findBodySpec($responseOperationAddress)) {
                $testSuite->addTest(new ResponseBodyParamsValidationTest($testResult->response, $specification, $responseOperationAddress, $responseBodySpec));
            }
        }

        return $testSuite;
    }
}
