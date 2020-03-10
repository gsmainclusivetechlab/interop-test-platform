<?php declare(strict_types=1);

namespace App\Testing\Extensions;

use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestFailureHook;

class TestExecutionExtension implements AfterSuccessfulTestHook, AfterTestFailureHook
{
    public function executeAfterSuccessfulTest(string $test, float $time): void
    {

    }

    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        dd($test);
    }
}
