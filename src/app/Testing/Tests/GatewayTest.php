<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Constraints\ValidationPasses;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;

class GatewayTest extends Assert implements Test
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $requestValidationRules = [];

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $responseValidationRules = [];

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request, array $requestValidationRules = [], array $responseValidationRules = [])
    {
        $this->request = $request;
        $this->requestValidationRules = $requestValidationRules;
        $this->responseValidationRules = $responseValidationRules;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?: new TestResult;
        Timer::start();
        $result->startTest($this);

        try {
            $this->response = $this->createHttpClient()->send($this->request);
            $this->assertThat($this->getRequestAsArray(), new ValidationPasses($this->requestValidationRules));
            $this->assertThat($this->getResponseAsArray(), new ValidationPasses($this->responseValidationRules));
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, Timer::stop());
        } catch (RequestException $e) {
            $result->addError($this, $e, Timer::stop());
        }

        $result->endTest($this, Timer::stop());
        return $result;
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * @return Client
     */
    protected function createHttpClient()
    {
        return new Client(['http_errors' => false]);
    }

    /**
     * @return array
     */
    public function getRequestAsArray()
    {
        return [
            'body' => $this->request->getParsedBody(),
            'query' => $this->request->getQueryParams(),
            'headers' => $this->request->getHeaders(),
        ];
    }

    /**
     * @return array
     */
    public function getResponseAsArray()
    {
        return [
            'code' => $this->response->getStatusCode(),
            'body' => $this->response->getBody()->getContents(),
            'headers' => $this->response->getHeaders(),
        ];
    }
}
