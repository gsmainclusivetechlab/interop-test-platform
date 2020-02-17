<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\SetJsonHeaders;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class TestingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', /*ValidateTraceContext::class,*/ SetJsonHeaders::class]);
    }

    public function handle(ServerRequestInterface $request)
    {
        dd($request);

        $client = new Client();

        try {
            $response = $client->send($request, []);
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }

//    protected function createTestRunner()
//    {
//        $testSuite = new TestSuite();
//        $testSuite->addTest(new RequestTest(request()));
//
//        $testRunner = new TestRunner();
//        $testResult = $testRunner->run($testSuite);
//
//        dd($testResult);
//    }
}
