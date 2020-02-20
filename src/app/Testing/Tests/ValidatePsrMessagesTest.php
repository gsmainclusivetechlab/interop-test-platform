<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Constraints\ValidationPasses;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianBergmann\Timer\Timer;

class ValidatePsrMessagesTest extends Assert implements Test
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $requestValidationRules = [];

    /**
     * @var array
     */
    protected $responseValidationRules = [];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, array $requestValidationRules = [], array $responseValidationRules = [])
    {
        $this->request = $request;
        $this->response = $response;
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
            $this->assertThat($this->getRequestAsArray(), new ValidationPasses($this->requestValidationRules));
            $this->assertThat($this->getResponseAsArray(), new ValidationPasses($this->responseValidationRules));
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, Timer::stop());
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
