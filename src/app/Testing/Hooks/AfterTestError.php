<?php declare(strict_types=1);

namespace App\Testing\Hooks;

use PHPUnit\Runner\AfterTestErrorHook;

class AfterTestError implements AfterTestErrorHook
{
    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        // TODO: Implement executeAfterTestError() method.
    }
}
