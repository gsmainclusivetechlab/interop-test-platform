<?php declare(strict_types=1);

namespace App\Testing;

use App\Testing\Listeners\TestListenerAdapter;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestResult;

class TestRunner
{
    /**
     * @var TestListener[]
     */
    protected $listeners = [];

    /**
     * @param Test $test
     * @return TestResult
     */
    public function run(Test $test): TestResult
    {
        return $test->run($this->buildResult());
    }

    /**
     * @return TestResult
     */
    protected function buildResult(): TestResult
    {
        $result = new TestResult();
        $result->addListener(new TestListenerAdapter());

        foreach ($this->listeners as $listener) {
            $result->addListener($listener);
        }

        return $result;
    }

    /**
     * @param TestListener $listener
     */
    public function addListener(TestListener $listener): void
    {
        $this->listeners[] = $listener;
    }
}
