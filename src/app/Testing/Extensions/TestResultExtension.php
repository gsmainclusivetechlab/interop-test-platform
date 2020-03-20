<?php declare(strict_types=1);

namespace App\Testing\Extensions;

use App\Models\TestResult;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class TestResultExtension implements BeforeFirstTestHook, AfterLastTestHook
{
    /**
     * @var TestResult
     */
    protected $result;

    /**
     * @param TestResult $result
     */
    public function __construct(TestResult $result)
    {
        $this->result = $result;
    }

    /**
     * @return void
     */
    public function executeBeforeFirstTest(): void
    {

    }

    /**
     * @return void
     */
    public function executeAfterLastTest(): void
    {
        $this->result->testRun()->increment($this->result->isSuccessful() ? 'successful' : 'unsuccessful');
    }
}
