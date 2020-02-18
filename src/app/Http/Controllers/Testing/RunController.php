<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\Environment;
use App\Models\TestSessionCase;
use App\Testing\TestRunner;
use App\Testing\Tests\SendRequestTest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
    }

    public function __invoke(TestSessionCase $sessionCase, ServerRequestInterface $request, string $path)
    {
        $environment = Environment::first();
        $step = $sessionCase->steps()
            ->where('path', $path)
            ->where('method', $request->getMethod())
            ->whereHas('targetSpecification')
            ->first();

        if ($step === null) {
            abort(404);
        }

        $suite = new TestSuite(SendRequestTest::class);
        $runner = new TestRunner();
        $result = $runner->run($suite);

        dd($result);

        $uri = (new Uri($environment->parse($step->targetSpecification->server)))
            ->withPath($path);

        try {
            $response = (new Client())->send($request->withUri($uri));
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
