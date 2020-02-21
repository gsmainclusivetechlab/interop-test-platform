<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Testing\Constraints\ValidationPasses;
use App\Testing\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class ValidateRequestTest extends TestCase
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @param ServerRequestInterface $request
     * @param array $validationRules
     */
    public function __construct(ServerRequestInterface $request, array $validationRules = [])
    {
        $this->request = $request;
        $this->validationRules = $validationRules;
    }

    /**
     * @return void
     */
    public function test()
    {
        $this->assertThat($this->getRequestAsArray(), new ValidationPasses($this->validationRules));
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getRequestAsArray()
    {
        return [
            'uri' => $this->request->getUri(),
            'method' => $this->request->getMethod(),
            'headers' => $this->request->getHeaders(),
            'query' => $this->request->getQueryParams(),
            'body' => $this->request->getParsedBody(),
        ];
    }
}
