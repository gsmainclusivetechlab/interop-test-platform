<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TestStatusEnum;
use App\Models\TestResult;
use App\Testing\Listeners\TestExecutionListener;
use App\Testing\TestRequest;
use App\Testing\TestRequestOptions;
use App\Testing\TestResponse;
use App\Testing\TestRunner;
use App\Testing\Tests\SomeTest;
use App\Testing\TestSuiteLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationData;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sessions = auth()->user()->sessions()
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }

    public function test(ServerRequestInterface $request)
    {
        $data = [
            'body' => [
                'amount' => 100,
                'test' => [
                    1,
                    2,
                    3,
                ],
            ],
        ];

//        $attributeData = ValidationData::extractDataFromPath(
//            ValidationData::getLeadingExplicitAttributePath('body.*'), $data
//        );
        // $this->parseData($data)

        $suite = new TestSuite();
        $suite->addTestSuite(new TestSuite(SomeTest::class));
//        $suite->addTest(new SomeTest());
        $runner = new TestRunner();
        $result = $runner->run($suite);

        dd($result);

//        dd($request1);

//        $testResult = TestResult::first();
//        $testStep = $testResult->testStep;

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($customOptions) {
            $options = TestRequestOptions::fromRequest($request)->merge($customOptions);

            dd($options->getJson());

            $testRequest = new TestRequest($request);

            return $request;
        }));
        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
            $testResponse = new TestResponse($response);
            return $response;
        }));

        $promise = (new Client(['handler' => $stack, 'http_errors' => false]))->sendAsync($request);
        $promise->then(function (ResponseInterface $response) {
//            dd($response);
        }, function (RequestException $e) {
//            dd($e);
        })->wait();

        dd($promise);
    }
}
