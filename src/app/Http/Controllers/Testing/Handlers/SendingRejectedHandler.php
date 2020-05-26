<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Exceptions\TestMismatchException;
use App\Models\TestResult;
use SebastianBergmann\Timer\Timer;
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
        Timer::start();
        $this->testResult = $testResult;
    }

    /**
     * @param Throwable $exception
     * @return Throwable
     */
    public function __invoke(Throwable $exception)
    {
        $this->testResult->fail(Timer::stop(), $exception->getMessage());
        $this->testResult->testRun->complete();

        throw new TestMismatchException($this->testResult->session, 500, $exception->getMessage());
    }
}
