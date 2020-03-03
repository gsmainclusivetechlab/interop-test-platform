<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Runner\TestHook;
use PHPUnit\Runner\TestListenerAdapter;

class TestRunner
{
    /**
     * @var TestHook[]
     */
    private $extensions = [];

    /**
     * @param Test $test
     * @return TestResult
     */
    public function run(Test $test): TestResult
    {
        $result = $this->createResult();
        $listener = $this->createListener();

        foreach ($this->extensions as $extension) {
            $listener->add($extension);
        }

        $result->addListener($listener);
        $test->run($result);

        return $result;
    }

    /**
     * @return TestResult
     */
    protected function createResult(): TestResult
    {
        return new TestResult();
    }

    /**
     * @return TestListenerAdapter
     */
    protected function createListener(): TestListenerAdapter
    {
        return new TestListenerAdapter();
    }

    /**
     * @param TestHook $extension
     */
    public function addExtension(TestHook $extension): void
    {
        $this->extensions[] = $extension;
    }
}
