<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TestRequestScript;
use App\Testing\Tests\ValidateRequestTest;
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
        $suite->addTest(new ValidateRequestTest($request, new TestRequestScript()));
        dd($suite->run());

        $sessions = auth()->user()->sessions()
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
