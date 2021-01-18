<?php

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Testing\TestCase;
use PHPUnit\Framework\AssertionFailedError;
use Route;

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
        return 'Was encrypted?';
    }

    public function getActual(): array
    {
        return [
            'mtls' => $this->getEncryptionStatus(),
        ];
    }

    public function getExpected(): array
    {
        return ['mtls' => true];
    }

    protected function test()
    {
        if (!$this->getEncryptionStatus()) {
            throw new AssertionFailedError();
        }
    }

    protected function getEncryptionStatus(): bool
    {
        return !Route::currentRouteNamed('testing*') ||
            Route::currentRouteNamed('testing.sut');
    }
}
