<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Specification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class CallbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', ValidateTraceContext::class, SetJsonHeaders::class]);
    }

    public function __invoke(Specification $specification, ServerRequestInterface $request, string $path)
    {
        dd($specification);

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
