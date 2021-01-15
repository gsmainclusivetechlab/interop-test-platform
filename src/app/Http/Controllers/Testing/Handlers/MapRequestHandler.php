<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Models\TestSetup;
use App\Http\Client\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Str;

class MapRequestHandler
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @param TestResult $testResult
     */
    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $testRequest = new Request($request);

        /*if (
            !$this->testResult->session->hasComponent(
                $this->testResult->testStep->source
            ) &&
            ($testRequestSetups = $this->testResult->testStep
                ->testSetups()
                ->ofType(TestSetup::TYPE_REQUEST)
                ->get())
        ) {
            foreach ($testRequestSetups as $testRequestSetup) {
                $testRequest = $testRequest->withSetup($testRequestSetup);
            }
        }*/

        if (
            !$this->testResult->session->hasComponent(
                $this->testResult->testStep->source
            )
        ) {
            $testRequest = $testRequest->withSubstitutions(
                $this->testResult->testRun->testResults,
                $this->testResult->session
            );
        }

        $testRequestData = array_merge($data = $testRequest->toArray(), [
            'uri' => Str::startsWith($data['uri'], 'http')
                ? $data['uri']
                : Str::start($data['uri'], '/'),
            'path' => Str::start($data['path'], '/'),
        ]);

        $this->testResult->update(['request' => $testRequestData]);

        if ($testRequest->isEmptyBody()) {
            $data = $testRequest->toArray();
            $testRequest = new Request(
                new ServerRequest(
                    $data['method'],
                    $data['uri'],
                    $data['headers'],
                    null
                )
            );
        }

        return $testRequest->toPsrRequest();
    }
}
