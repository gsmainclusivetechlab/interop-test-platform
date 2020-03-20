<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResponseScript;
use App\Testing\TestCase;
use App\Testing\TestResponse;

class ValidateResponseTest extends TestCase
{
    /**
     * @var TestResponseScript
     */
    protected $script;

    /**
     * @var TestResponse
     */
    protected $response;

    /**
     * @param TestResponseScript $script
     * @param TestResponse $request
     */
    public function __construct(TestResponseScript $script, TestResponse $response)
    {
        $this->script = $script;
        $this->response = $response;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed($this->response->toArray(), $this->script->rules);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->script->name;
    }
}
