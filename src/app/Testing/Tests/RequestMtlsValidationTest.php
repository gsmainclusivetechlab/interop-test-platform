<?php

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Testing\TestCase;
use PHPUnit\Framework\AssertionFailedError;

class RequestMtlsValidationTest extends TestCase
{
    /** @var TestResult */
    protected $testResult;

    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    public function getName(): string
    {
        return 'Request: mTLS';
    }

    public function getActual(): array
    {
        return [
            'mtls' => (bool) $this->testResult->testStep->mtls,
        ];
    }

    public function getExpected(): array
    {
        return ['mtls' => true];
    }

    protected function test()
    {
        if ($this->testResult->testStep->mtls) {
            throw new AssertionFailedError();
        }
    }
}
