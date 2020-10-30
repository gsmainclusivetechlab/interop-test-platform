<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Models\TestResult;
use Psr\Http\Message\RequestInterface;

class ProcessPendingRequest
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @param RequestInterface $request
     * @param TestResult $testResult
     */
    public function __construct(
        RequestInterface $request,
        TestResult $testResult
    ) {
        $this->request = $request;
        $this->testResult = $testResult;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return (new PendingRequest())
            ->mapRequest(new MapRequestHandler($this->testResult))
            ->mapResponse(new MapResponseHandler($this->testResult))
            ->transfer($this->request)
            ->then(new SendingFulfilledHandler($this->testResult))
            ->otherwise(new SendingRejectedHandler($this->testResult))
            ->wait();
    }
}
