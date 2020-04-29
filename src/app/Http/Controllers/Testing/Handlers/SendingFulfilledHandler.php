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
        $testResult = (new TestRunner())->run($this->testResult);

        if ($testResult->testStep->isLastPosition()) {
            $testResult->testRun->complete();
        }

        return $response;
    }
}
