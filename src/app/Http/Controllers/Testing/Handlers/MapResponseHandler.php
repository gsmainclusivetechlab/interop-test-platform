<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Enums\HttpTypeEnum;
use App\Models\TestResult;
use App\Testing\TestResponse;
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
        $testResponse = new TestResponse($response);

        if ($testResponseSetups = $this->testResult->testStep->testSetups()->ofType(HttpTypeEnum::RESPONSE)->get()) {
            foreach ($testResponseSetups as $testResponseSetup) {
                $testResponse = $testResponse->withSetup($testResponseSetup);
            }
        }

        $this->testResult->update(['response' => $testResponse]);

        return $testResponse->toPsrResponse();
    }
}
