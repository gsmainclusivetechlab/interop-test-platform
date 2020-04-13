<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Models\TestSetup;
use App\Testing\TestRequest;
use Psr\Http\Message\RequestInterface;

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
        $testRequest = new TestRequest($request);

        if ($testRequestSetups = $this->testResult->testStep->testSetups()->ofType(TestSetup::TYPE_REQUEST)->get()) {
            foreach ($testRequestSetups as $testRequestSetup) {
                $testRequest = $testRequest->withSetup($testRequestSetup);
            }
        }

        $this->testResult->update(['request' => $testRequest]);

        return $testRequest->toPsrRequest();
    }
}
