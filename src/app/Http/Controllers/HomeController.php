<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\TestRun;
use App\Models\TestStep;
use App\Testing\TestRequest;
use App\Testing\TestResponse;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use function foo\func;

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
//        $suite = new TestSuite();
//        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
//        $requestSuite->setRequest($request);
//        $suite->addTestSuite($requestSuite);
//        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
//        $requestSuite->setRequest($request);
//        $suite->addTestSuite($requestSuite);
//
//        $loader = new TestSuiteLoader();
//
//        dd($loader->load());
//
//        $runner = new TestRunner();
//        $runner->addExtension(new TestExecutionExtension());
//
//        dd($runner->run($suite));

//        $stack = new HandlerStack();
//
//        dd($stack);

//        $run = TestRun::firstWhere('id', 206);
//        $step = $run->testSteps()
//            ->whereHas('source', function ($query) {
//                $query->whereHas('apiService', function ($query) {
//                    $query->where('server', 'http://172.16.1.72:8084');
//                });
//            })
//            ->offset($run->testResults()
//                ->whereHas('testStep', function ($query) {
//                    $query->whereHas('source', function ($query) {
//                        $query->whereHas('apiService', function ($query) {
//                            $query->where('server', 'http://172.16.1.72:8084');
//                        });
//                    });
//                })->count());
//
//        dd($run->testSteps()
//            ->whereHas('source', function ($query) {
//                $query->whereHas('apiService', function ($query) {
//                    $query->where('server', 'like', '%172.16.1.72:8084');
//                });
//            })->count());
//        dd($run->testResults()
//            ->whereHas('testStep', function ($query) {
//                $query->whereHas('source', function ($query) {
//                    $query->whereHas('apiService', function ($query) {
//                        $query->where('server', 'http://172.16.1.72:8084');
//                    });
//                });
//            })->get());

        dd(TestResult::firstWhere('id', 2347)->request);
        dd((new TestResponse(new Response()))->toArray());

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
