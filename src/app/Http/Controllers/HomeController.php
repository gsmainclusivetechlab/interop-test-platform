<?php declare(strict_types=1);

namespace App\Http\Controllers;

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

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
