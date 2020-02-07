<?php

namespace App\Testing;

use App\Testing\Hooks\AfterSuccessfulTest;
use App\Testing\Hooks\AfterTestError;
use App\Testing\Hooks\AfterTestFailure;
use App\Testing\Hooks\AfterTestWarning;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Runner\TestListenerAdapter;

final class TestRunner
{
    /**
     * @param Test $test
     * @return TestResult
     */
    public function run(Test $test)
    {
        $listener = $this->createListener();
        $listener->add(new AfterSuccessfulTest);
        $listener->add(new AfterTestError());
        $listener->add(new AfterTestFailure);
        $listener->add(new AfterTestWarning);
        $result = $this->createResult();
        $result->addListener($listener);
        $result = $test->run($result);

        return $result;
    }

    /**
     * @return TestResult
     */
    protected function createResult()
    {
        return new TestResult;
    }

    /**
     * @return TestListenerAdapter
     */
    protected function createListener()
    {
        return new TestListenerAdapter;
    }
}
