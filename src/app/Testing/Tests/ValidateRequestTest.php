<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestRequestScript;
use App\Testing\TestCase;
use App\Testing\TestRequest;

class ValidateRequestTest extends TestCase
{
    /**
     * @var TestRequestScript
     */
    protected $script;

    /**
     * @var TestRequest
     */
    protected $request;

    /**
     * @param TestRequestScript $script
     * @param TestRequest $request
     */
    public function __construct(TestRequestScript $script, TestRequest $request)
    {
        $this->script = $script;
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function doTest()
    {
        $this->assertValidationPassed($this->request->toArray(), $this->script->rules);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->script->name;
    }
}
