<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestScript;
use App\Models\TestResult;
use App\Testing\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\AssertionFailedError;

class ValidateResponseScriptTest extends TestCase
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
        $validator = Validator::make(
            $this->testResult->testResponse->attributesToArray(),
            (array) $this->testScript->rules,
            (array) $this->testScript->messages,
            (array) $this->testScript->attributes
        );

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            throw new AssertionFailedError(implode(PHP_EOL, $validator->errors()->all()));
        }
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
