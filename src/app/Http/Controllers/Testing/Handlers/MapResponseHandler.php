<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Models\TestSetup;
use App\Http\Client\Response;
use Psr\Http\Message\ResponseInterface;

class MapResponseHandler
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
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response)
    {
        $testResponse = new Response($response);

        /*if (
            !$this->testResult->session->hasComponent(
                $this->testResult->testStep->target
            ) &&
            ($testResponseSetups = $this->testResult->testStep
                ->testSetups()
                ->ofType(TestSetup::TYPE_RESPONSE)
                ->get())
        ) {
            foreach ($testResponseSetups as $testResponseSetup) {
                $testResponse = $testResponse->withSetup($testResponseSetup);
            }
        }*/

        if (
            !$this->testResult->session->hasComponent(
                $this->testResult->testStep->target
            )
        ) {
            $testResponse = $testResponse->withSubstitutions(
                $this->testResult->testRun->testResults,
                $this->testResult->session
            );
        }
        $this->testResult->update(['response' => $testResponse]);

        return $testResponse->toPsrResponse();
    }
}
