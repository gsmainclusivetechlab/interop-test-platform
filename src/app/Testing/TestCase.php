<?php

namespace App\Testing;

use PHPUnit\Framework\TestCase as BaseTestCase;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Timer\Timer;

class TestCase extends BaseTestCase
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->assertTrue(true);
//        if ($result === null) {
//            $result = new TestResult;
//        }
//
//        $result->startTest($this);
//        Timer::start();
//
//        try {
//            \PHPUnit\Framework\Assert::assertTrue(false);
//        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
//            $stopTime = Timer::stop();
//            $result->addFailure($this, $e, $stopTime);
//        }
//
//        $stopTime = Timer::stop();
//        $result->endTest($this, $stopTime);
//
//        dd($result);
//
//        return $result;
    }
}
