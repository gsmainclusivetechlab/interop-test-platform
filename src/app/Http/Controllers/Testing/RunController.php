<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\TestPlan;
use App\Models\TestRun;
use App\Testing\Constraints\ValidationPasses;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;
use Throwable;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
        // $this->middleware('log')->only('index');
    }

    /**
     * @param ServerRequestInterface $request
     * @param TestPlan $plan
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, TestPlan $plan, string $path = null)
    {
        $step = $plan->steps()
            ->whereRaw('? like path', $path)
            ->where('method', $request->getMethod())
            ->firstOrFail();

        $run = TestRun::create([
            'case_id' => $plan->case_id,
            'session_id' => $plan->session_id,
        ]);

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId(str_replace('-', '', $run->uuid))
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);
        // $response = (new Client(['http_errors' => false]))->send($request);

        Timer::start();

        try {
            Assert::assertThat((array) $request, new ValidationPasses($step->expected_request));
//            Assert::assertThat((array) $response, new ValidationPasses($step->expected_response));
        } catch (AssertionFailedError $exception) {
            dd($exception->getMessage());
        } catch (Throwable $exception) {
            dd($exception->getMessage());
        } finally {
            $time = Timer::stop();
        }

        dd($request);

//        $result = $run->results()->create([
//            'step_id' => $step->id,
//            'time' => floor($time * 1000),
//            'request' => (array) $request,
//            'response' => (array) $response,
//        ]);
    }

    protected function doTest()
    {

    }
}
