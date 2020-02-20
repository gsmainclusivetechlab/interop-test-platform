<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\Environment;
use App\Models\TestCase;
use App\Models\TestSession;
use App\Testing\TestRunner;
use App\Testing\Tests\ValidateRequestTest;
use App\Testing\Tests\ValidateResponseTest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
    }

    public function __invoke(ServerRequestInterface $request, TestSession $session, TestCase $case, string $path = null)
    {
        $environment = Environment::first();
        $case = $session->cases()->where('case_id', $case->id)->firstOrFail();
        $step = $case->steps()
            ->where('path', $path)
            ->where('method', $request->getMethod())
            ->whereHas('targetSpecification')
            ->firstOrFail();

        dd($step);

        $uri = (new Uri($environment->parse($step->targetSpecification->server)))->withPath($path);
        $request = $request->withUri($uri);
        $response = (new Client(['http_errors' => false]))->send($request);

        $suite = new TestSuite();
        $suite->addTest(new ValidateRequestTest($request));
        $suite->addTest(new ValidateResponseTest($response));

        $runner = new TestRunner();
        $result = $runner->run($suite);

        dd($result);
    }
}
