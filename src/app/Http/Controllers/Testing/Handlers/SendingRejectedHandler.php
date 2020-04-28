<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestRunner;
use GuzzleHttp\Exception\RequestException;
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
     * @return RequestException|\GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface|Throwable|null
     */
    public function __invoke(Throwable $exception)
    {
        $testResult = (new TestRunner())->run($this->testResult);
        $testResult->testRun->complete();

        return ($exception instanceof RequestException && $exception->getResponse()) ?
            $exception->getResponse() :
            $exception;
    }
}
