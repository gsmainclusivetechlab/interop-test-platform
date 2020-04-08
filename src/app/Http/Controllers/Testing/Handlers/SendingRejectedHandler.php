<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestListenerAdapter;
use App\Testing\TestRunner;
use App\Testing\TestSuiteLoader;
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
        $loader = new TestSuiteLoader($this->testResult);
        $runner = new TestRunner();
        $runner->addListener(new TestListenerAdapter($this->testResult));
        $time = Timer::stop();
        $this->testResult->complete($runner->run($loader->load())->wasSuccessful(), $time);
        $this->testResult->testRun->complete();

        return $exception;
    }
}
