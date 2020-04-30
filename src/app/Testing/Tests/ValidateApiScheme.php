<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\ApiScheme;
use App\Models\TestResult;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Throwable;

class ValidateApiScheme
{
    /**
     * @var ApiScheme
     */
    protected $apiScheme;

    /**
     * @param ApiScheme $apiScheme
     */
    public function __construct(ApiScheme $apiScheme)
    {
        $this->apiScheme = $apiScheme;
    }

    /**
     * @param TestResult $testResult
     * @return TestResult
     * @throws Throwable
     */
    public function __invoke(TestResult $testResult)
    {
        $validator = (new ValidatorBuilder)->fromSchema($this->apiScheme->openapi);
        $testExecution = $testResult->testExecutions()->make([
            'name' => __(':name API Schema Validation', ['name' => $this->apiScheme->name]),
        ]);

        try {
            $operationAddress = $validator->getRequestValidator()->validate($testResult->request->toPsrRequest());
            $validator->getResponseValidator()->validate($operationAddress, $testResult->response->toPsrResponse());
            $testExecution->pass();
        } catch (Throwable $e) {
            $testExecution->fail($e->getMessage());
            throw $e;
        }

        return $testResult;
    }
}
