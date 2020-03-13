<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResponseScript;
use App\Testing\TestCase;
use Psr\Http\Message\ResponseInterface;

class ValidateResponseTest extends TestCase
{
    /**
     * @var TestResponseScript
     */
    protected $script;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param TestResponseScript $script
     * @param ResponseInterface $request
     */
    public function __construct(TestResponseScript $script, ResponseInterface $response)
    {
        $this->script = $script;
        $this->response = $response;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed([
            'status' => $this->response->getStatusCode(),
            'headers' => $this->response->getHeaders(),
            'body' => json_decode($this->response->getBody()->getContents(), true),
        ], $this->script->rules);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->script->name;
    }
}
