<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestRequestScript;
use App\Testing\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class ValidateRequestTest extends TestCase
{
    /**
     * @var TestRequestScript
     */
    protected $script;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @param TestRequestScript $script
     * @param ServerRequestInterface $request
     */
    public function __construct(TestRequestScript $script, ServerRequestInterface $request)
    {
        $this->script = $script;
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed([
            'uri' => $this->request->getUri()->__toString(),
            'method' => $this->request->getMethod(),
            'headers' => $this->request->getHeaders(),
            'body' => json_decode($this->request->getBody()->getContents(), true),
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
