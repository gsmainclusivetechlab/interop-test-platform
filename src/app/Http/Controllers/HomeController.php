<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Testing\Handlers\BeforeSendingHandler;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Controllers\Testing\PendingRequest;
use App\Http\Resources\SessionResource;
use App\Models\ApiSpec;
use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\TestScriptLoader;
use App\Testing\TestSpecLoader;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Inertia\Inertia;
use PHPUnit\Framework\TestResult as TestSuiteResult;
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
     * @return \Inertia\Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
//        $uri = UriResolver::resolve(
//            new Uri('http://mojaloop920.s21.jc'),
//            new Uri('authorizations/be773dea-5b20-45f4-a85b-4660b258ff10')
//        );
//        $request = $request->withUri($uri->withQuery('amount=1011&authenticationType=OTP&currency=USD&retriesLeft=3'));
//        $request = $request->withHeader('host', 'mojaloop920.s21.jc')
//            ->withHeader('content-length', '')
//            ->withHeader('content-type', 'application/vnd.interoperability.transfers+json;version=1.0');
//
//        $response = (new PendingRequest())
//            ->transfer($request)
//            ->wait();
//
//        dd($response);

        return Inertia::render('home', [
            'sessions' => SessionResource::collection(
                auth()->user()->sessions()
                    ->with([
                        'testCases' => function ($query) {
                            return $query->with(['lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->latest()
                    ->limit(12)
                    ->get()
            ),
        ]);
    }
}
