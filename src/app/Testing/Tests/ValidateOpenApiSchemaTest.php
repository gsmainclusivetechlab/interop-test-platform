<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\ApiScheme;
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
     * @var ApiScheme
     */
    protected $apiScheme;

    /**
     * @param TestResult $testResult
     * @param ApiScheme $apiScheme
     */
    public function __construct(TestResult $testResult, ApiScheme $apiScheme)
    {
        $this->testResult = $testResult;
        $this->apiScheme = $apiScheme;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = (new ValidatorBuilder)->fromSchema($this->apiScheme->openapi);

        try {
            $operationAddress = $validator->getRequestValidator()->validate($this->testResult->request->toPsrRequest());
            $validator->getResponseValidator()->validate($operationAddress, $this->testResult->response->toPsrResponse());
        } catch (ValidationFailed $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __(':name API Schema Validation', ['name' => $this->apiScheme->name]);
    }
}
