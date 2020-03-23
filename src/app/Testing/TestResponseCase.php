<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestScript;

abstract class TestResponseCase extends TestCase
{
    /**
     * @var TestScript
     */
    protected $script;

    /**
     * @var TestResponse
     */
    protected $response;

    /**
     * @param TestScript $script
     * @param TestResponse $request
     */
    public function __construct(TestScript $script, TestResponse $response)
    {
        $this->script = $script;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->script->name;
    }
}
