<?php declare(strict_types=1);

namespace App\Testing\Listeners;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class TestListenerAdapter implements TestListener
{
    use TestListenerDefaultImplementation;

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {

    }

    public function endTest(Test $test, float $time): void
    {

    }

    public function endTestSuite(TestSuite $suite): void
    {

    }
}
