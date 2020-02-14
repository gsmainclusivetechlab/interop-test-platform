<?php declare(strict_types=1);

namespace App\Testing\Hooks;

use PHPUnit\Runner\AfterTestWarningHook;

class AfterTestWarning implements AfterTestWarningHook
{
    public function executeAfterTestWarning(string $test, string $message, float $time): void
    {
        // TODO: Implement executeAfterTestWarning() method.
    }
}
