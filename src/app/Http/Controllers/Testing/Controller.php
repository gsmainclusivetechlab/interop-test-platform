<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestRequest;
use App\Models\TestResponse;
use App\Models\TestResult;
use App\Testing\TestRequestSuiteLoader;
use App\Testing\TestRunner;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller extends BaseController
{

    protected function doTest(ServerRequestInterface $request, TestResult $testResult)
    {
        $suite = (new TestRequestSuiteLoader($testResult))->load();

        dd($suite);

        $runner = new TestRunner();

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($testResult) {
            $testRequest = TestRequest::makeFromRequest($request)->testResult()->associate($testResult);

            return $request;
        }));
        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) use ($testResult) {
            $testResponse = TestResponse::makeFromResponse($response)->testResult()->associate($testResult);

            return $response;
        }));
        $promise = (new Client(['handler' => $stack, 'http_errors' => false]))->sendAsync($request);
        $promise->then(function (ResponseInterface $response) {
            dd($response);
        }, function (RequestException $e) {
            dd($e);
        })->wait();
    }
}
