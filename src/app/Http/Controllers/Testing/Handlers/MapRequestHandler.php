<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestRequest;
use App\Models\TestResult;
use Psr\Http\Message\RequestInterface;

class MapRequestHandler
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
            $testRequest = TestRequest::makeFromRequest($request)->testResult()->associate($this->testResult);
            $testRequest->save();

            return $handler($request, $options);
        };
    }
}
