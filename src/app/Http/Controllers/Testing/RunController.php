<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Headers\TracestateHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Component;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\TestResult;
use App\Models\TestRun;
use App\Testing\TestExecutionListener;
use App\Testing\TestScriptLoader;
use App\Testing\TestSpecLoader;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use PHPUnit\Framework\TestResult as TestSuiteResult;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class]);
        $this->middleware([ValidateTraceContext::class])->only(['simulator']);
    }

    public function run(Session $session, TestCase $testCase, string $path, ServerRequestInterface $request)
    {
        $testStep = $testCase->testSteps()->firstOrFail();
        $testRun = $session->testRuns()->create(['test_case_id' => $testStep->test_case_id]);
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        if ($sut = $session->components()->whereKey($testStep->target->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $testStep->target->base_url;
        }

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)))
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent);

        $this->doTestAfterResponse($testResult);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->sendAsync()
            ->otherwise(function ($e) use ($testResult) {
                $testResult->fail($e->getMessage());
                $testResult->testRun->complete();

                return $e;
            })
            ->wait();
    }

    public function sut(Session $session, Component $component, Component $connection, string $path, ServerRequestInterface $request)
    {
        $testStep = $session->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->whereRaw('JSON_CONTAINS(?, test_steps.trigger)', [$request->getBody()->getContents()])
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('position', '=', 1);
                    $query->whereDoesntHave('testRuns', function ($query) {
                        $query->incompleted();
                    });
                })->orWhere(function ($query) {
                    $query->where('position', '!=', 1);
                    $query->whereHas('testRuns', function ($query) {
                        $query->incompleted();
                    });
                });
            })
            ->firstOrFail();

        $testRun = $session->testRuns()
            ->incompleted()
            ->where('test_case_id', $testStep->test_case_id)
            ->firstOrCreate(['test_case_id' => $testStep->test_case_id]);

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);

        if ($sut = $session->components()->whereKey($connection->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $connection->base_url;
        }

        $request = $request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)))
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)->withoutHeader(TracestateHeader::NAME);

        $this->doTestAfterResponse($testResult);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->sendAsync()
            ->otherwise(function ($e) use ($testResult) {
                $testResult->fail($e->getMessage());
                $testResult->testRun->complete();

                return $e;
            })
            ->wait();
    }

    public function simulator(Component $component, Component $connection, string $path, ServerRequestInterface $request)
    {
        $trace = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $trace->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->offset(
                $testRun->testResults()
                    ->whereHas('testStep', function ($query) use ($component, $connection, $request, $path) {
                        $query->where('method', $request->getMethod())
                            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
                            ->whereHas('source', function ($query) use ($component) {
                                $query->whereKey($component->getKey());
                            })
                            ->whereHas('target', function ($query) use ($connection) {
                                $query->whereKey($connection->getKey());
                            });
                    })
                    ->count()
            )
            ->firstOrFail();

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        if ($sut = $testRun->session->components()->whereKey($connection->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $testStep->target->base_url;
        }

        $request = $request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)));

        $this->doTestAfterResponse($testResult);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->sendAsync()
            ->otherwise(function ($e) use ($testResult) {
                $testResult->fail($e->getMessage());
                $testResult->testRun->complete();

                return $e;
            })
            ->wait();
    }

    protected function doTestAfterResponse(TestResult $testResult)
    {
        app()->terminating(function () use ($testResult) {
            $testSuite = new TestSuite();
            $testSuite->addTestSuite((new TestSpecLoader())->load($testResult));
            $testSuite->addTestSuite((new TestScriptLoader())->load($testResult));
            $testSuiteResult = new TestSuiteResult();
            $testSuiteResult->addListener(new TestExecutionListener($testResult));
            $testSuiteResult = $testSuite->run($testSuiteResult);

            if ($testSuiteResult->wasSuccessful()) {
                $testResult->pass();
            } else {
                $testResult->fail();
            }

            if ($testResult->testStep->isLastPosition()) {
                $testResult->testRun->complete();
            }
        });
    }
}
