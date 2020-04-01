<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResponse;
use App\Models\TestResult;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MapResponseHandler
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
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($request, $options)->then(function (ResponseInterface $response) {
                $testResponse = TestResponse::makeFromResponse($response)->testResult()->associate($this->testResult);

                if ($testResponseSetups = $this->testResult->testStep->testResponseSetups) {
                    foreach ($testResponseSetups as $testResponseSetup) {
                        $testResponse->mergeSetup($testResponseSetup);
                    }
                }

                $testResponse->save();

                return $testResponse->toResponse();
            });
        };
    }
}
