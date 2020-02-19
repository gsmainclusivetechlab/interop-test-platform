<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestRun;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;

class TestRunner
{
    /**
     * @var TestRun
     */
    protected $run;

    /**
     * @param TestRun $run
     */
//    public function __construct(TestRun $run)
//    {
//        $this->run = $run;
//    }

    /**
     * @param Test $test
     * @return TestResult
     */
    public function run(Test $test)
    {
        $result = new TestResult;
        $result->addListener(new TestListener());
        $result = $test->run($result);

        return $result;
    }
}
