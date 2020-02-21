<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Constraints\ValidationPasses;
use App\Testing\TestCase;
use Psr\Http\Message\ResponseInterface;

class ValidateResponseTest extends TestCase
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @param ResponseInterface $response
     * @param array $validationRules
     */
    public function __construct(ResponseInterface $response, array $validationRules = [])
    {
        $this->response = $response;
        $this->validationRules = $validationRules;
    }

    /**
     * @return void
     */
    public function test()
    {
        $this->assertThat($this->getResponseAsArray(), new ValidationPasses($this->validationRules));
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
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
