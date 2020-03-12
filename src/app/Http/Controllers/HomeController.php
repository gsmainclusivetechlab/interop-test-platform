<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Testing\RequestTestSuite;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\TestSuiteLoader;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
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
    public function index(ServerRequestInterface $request)
    {
        $suite = new TestSuite();
        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
        $requestSuite->setRequest($request);
        $suite->addTestSuite($requestSuite);
        $requestSuite = new RequestTestSuite(ValidateRequestTest::class);
        $requestSuite->setRequest($request);
        $suite->addTestSuite($requestSuite);

//        $loader = new TestSuiteLoader();

        $result = new TestResult();
        $suite->run($result);

        dd($result);
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

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
