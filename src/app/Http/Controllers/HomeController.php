<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TestRequestScript;
use App\Testing\Extensions\TestExecutionExtension;
use App\Testing\TestRunner;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\Tests\ValidateResponseTest;
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
        $suite->addTest(new ValidateResponseTest($request, new TestRequestScript()));
//        $suite->addTest(new ValidateRequestTest($request, new TestRequestScript()));

        $runner = new TestRunner();
        $runner->addExtension(new TestExecutionExtension());
        dd($runner->run($suite));

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
