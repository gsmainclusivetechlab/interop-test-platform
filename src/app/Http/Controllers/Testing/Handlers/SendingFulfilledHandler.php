<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestRunner;
use Psr\Http\Message\ResponseInterface;

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
        (new TestRunner())->run($this->testResult);

        if (
            $this->testResult->testStep->isLastPosition()
            ||
            !($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)
        ) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
