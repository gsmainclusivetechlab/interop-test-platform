<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Constraints\ValidationPasses;
use App\Testing\TestCase;

class ValidateRequestScriptTest extends TestCase
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @var TestScript
     */
    protected $testScript;

    /**
     * @param TestResult $testResult
     * @param TestScript $testScript
     */
    public function __construct(TestResult $testResult, TestScript $testScript)
    {
        $this->testResult = $testResult;
        $this->testScript = $testScript;
    }

    /**
     * @return void
     */
    public function test()
    {
        $this->assertThat(
            $this->testResult->testRequest->attributesToArray(),
            new ValidationPasses((array) $this->testScript->rules, (array) $this->testScript->messages, (array) $this->testScript->attributes)
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->testScript->name;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->testScript->group;
    }
}
