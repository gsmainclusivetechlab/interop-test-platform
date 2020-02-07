<?php

namespace App\Testing\Hooks;

use PHPUnit\Runner\AfterSuccessfulTestHook;

class AfterSuccessfulTest implements AfterSuccessfulTestHook
{
    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        // TODO: Implement executeAfterSuccessfulTest() method.
    }
}
