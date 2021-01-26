<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\AssertionFailedError;

class RequestScriptValidationTest extends TestCase
{
    use ScriptValidationTest;

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
            $this->testResult->request->toArray(),
            $this->getExpected(),
            (array) $this->testScript->messages
        );

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            throw new AssertionFailedError(
                implode(PHP_EOL, $validator->errors()->all())
            );
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __('Request: :name', ['name' => $this->testScript->name]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return tap([], function (&$actual) {
            $expected = $this->getExpected();
            $data = $this->testResult->request->toArray();

            foreach (array_keys($expected) as $attribute) {
                $actual = array_merge(
                    $actual,
                    ValidationData::initializeAndGatherData($attribute, $data)
                );
            }
        });
    }
}
