<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\TestPlan;
use App\Models\TestRun;
use App\Models\TestStep;
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
        $this->middleware(ValidateTraceContext::class)->only('test');
    }

    /**
     * @param ServerRequestInterface $request
     * @param TestPlan $plan
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, TestPlan $plan, string $path = null)
    {
        /**
         * @var TestStep $step
         */
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
            ->withTraceId($run->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        return $this->doTest($request, $run, $step);
    }

    /**
     * @param ServerRequestInterface $request
     * @param TestRun $run
     * @param TestStep $step
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    protected function doTest(ServerRequestInterface $request, TestRun $run, TestStep $step)
    {
        $result = $run->results()->make([
            'step_id' => $step->id,
            'request' => $this->convertRequestToArray($request),
        ]);
        Timer::start();

        try {
            $response = (new Client(['http_errors' => false]))->send($request);
            $result->response = $this->convertResponseToArray($response);
            Assert::assertThat($result->request, new ValidationPasses(['title' => 'required|unique:posts|max:255']/*$step->expected_request*/));
            Assert::assertThat($result->response, new ValidationPasses($step->expected_response));
            $result->passed();
            return $response;
        } catch (AssertionFailedError $exception) {
            $result->failed($exception->getMessage());
            return $response;
        } catch (Throwable $exception) {
            $result->error($exception->getMessage());
            return $exception;
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function convertRequestToArray(ServerRequestInterface $request)
    {
        return [
            'uri' => $request->getUri(),
            'method' => $request->getMethod(),
            'headers' => $request->getHeaders(),
            'body' => $request->getParsedBody(),
            'query_params' => $request->getQueryParams(),
        ];
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function convertResponseToArray(ResponseInterface $response)
    {
        return [
            'status_code' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
        ];
    }
}
