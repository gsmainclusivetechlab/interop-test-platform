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
    }
}
