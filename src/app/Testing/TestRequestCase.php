<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestScript;

abstract class TestRequestCase extends TestCase
{
    /**
     * @var TestScript
     */
    protected $script;

    /**
     * @var TestRequest
     */
    protected $request;

    /**
     * @param TestScript $script
     * @param TestRequest $request
     */
    public function __construct(TestScript $script, TestRequest $request)
    {
        $this->script = $script;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->script->name;
    }
}
