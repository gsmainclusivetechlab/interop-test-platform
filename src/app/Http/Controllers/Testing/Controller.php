<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller as BaseController;
use App\Models\TestRun;
use App\Models\TestStep;
use App\Testing\Constraints\ValidationPasses;
use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;
use Throwable;

class Controller extends BaseController
{
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
            Assert::assertThat($result->request, new ValidationPasses($step->expected_request));
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
