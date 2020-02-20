<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\Environment;
use App\Models\TestCase;
use App\Models\TestRun;
use App\Models\TestSession;
use App\Testing\Tests\GatewayTest;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\uri_for;

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

        $run = TestRun::create([
            'case_id' => $case->id,
            'session_id' => $session->id,
        ]);

        $test = new GatewayTest($request->withUri(uri_for($environment->parse($step->targetSpecification->server))->withPath($path)), [], ['code' => 'in:(200)']);
        $result = $test->run();

        $runResult = $run->results()->create([
            'step_id' => $step->id,
            'time' => $result->time(),
            'request' => $test->getRequestAsArray(),
            'response' => $test->getResponseAsArray(),
        ]);

        dd($result);
    }
}
