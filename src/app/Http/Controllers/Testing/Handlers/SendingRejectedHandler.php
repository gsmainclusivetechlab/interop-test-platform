<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use GuzzleHttp\Exception\RequestException;
use SebastianBergmann\Timer\Timer;

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
     * @param RequestException $exception
     * @return RequestException
     */
    public function __invoke(RequestException $exception)
    {
        $time = Timer::stop();
        $this->testResult->unsuccessful($time);
        $this->testResult->testRun->complete();

        return $exception;
    }
}
