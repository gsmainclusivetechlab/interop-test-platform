<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use Throwable;

class SendingRejectedHandler
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @param TestResult $testResult
     */
    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @param Throwable $exception
     * @return Throwable
     */
    public function __invoke(Throwable $exception)
    {
//        $this->testResult->fail($exception->getMessage());
//        $this->testResult->testRun->complete();

        return $exception;
    }
}
