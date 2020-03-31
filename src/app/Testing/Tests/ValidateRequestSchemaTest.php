<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Enums\TestGroupEnum;
use App\Models\ApiService;
use App\Models\TestResult;
use App\Testing\Constraints\RequestSchemaValid;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\AssertionFailedError;

class ValidateRequestSchemaTest extends TestCase
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
    protected function test()
    {
        $psrRequest = $this->testResult->testRequest->toPsr();
        $this->assertThat($psrRequest, new RequestSchemaValid($this->apiService->scheme));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __(':name API Schema Validation', ['name' => $this->apiService->name]);
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return TestGroupEnum::REQUEST;
    }
}
