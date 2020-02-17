<?php declare(strict_types=1);

namespace App\Testing\Hooks;

use PHPUnit\Runner\AfterTestFailureHook;

class AfterTestFailure implements AfterTestFailureHook
{
    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        // TODO: Implement executeAfterTestFailure() method.
    }
}
