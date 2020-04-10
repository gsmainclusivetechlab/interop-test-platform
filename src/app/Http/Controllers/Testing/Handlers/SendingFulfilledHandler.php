<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestListenerAdapter;
use App\Testing\TestSuiteLoader;
use App\Testing\TestRunner;
use Psr\Http\Message\ResponseInterface;
use SebastianBergmann\Timer\Timer;

class SendingFulfilledHandler
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
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response)
    {
        $loader = new TestSuiteLoader($this->testResult);
        $runner = new TestRunner();
        $runner->addListener(new TestListenerAdapter($this->testResult));
        $time = Timer::stop();
        $result = $runner->run($loader->load());

        if ($result->wasSuccessful()) {
            $this->testResult->successful($time);
        } else {
            $this->testResult->unsuccessful($time);
        }

        if ($this->testResult->testStep->isLastPosition() || !($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
