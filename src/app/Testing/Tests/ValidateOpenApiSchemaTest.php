<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\ApiService;
use App\Models\TestResult;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\AssertionFailedError;

class ValidateOpenApiSchemaTest extends TestCase
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * @param TestResult $testResult
     * @param ApiService $apiService
     */
    public function __construct(TestResult $testResult, ApiService $apiService)
    {
        $this->testResult = $testResult;
        $this->apiService = $apiService;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = (new ValidatorBuilder)->fromSchema($this->apiService->scheme);

        try {
            $operationAddress = $validator->getRequestValidator()->validate($this->testResult->testRequest->toRequest());
            $validator->getResponseValidator()->validate($operationAddress, $this->testResult->testResponse->toResponse());
        } catch (ValidationFailed $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __(':name API Schema Validation', ['name' => $this->apiService->name]);
    }
}
