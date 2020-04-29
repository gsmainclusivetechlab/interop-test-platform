<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Models\TestScript;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;
use Throwable;

class ValidateRequestScriptTest
{
    /**
     * @var TestScript
     */
    protected $testScript;

    /**
     * @param TestScript $testScript
     */
    public function __construct(TestScript $testScript)
    {
        $this->testScript = $testScript;
    }

    /**
     * @param TestResult $testResult
     * @param callable $next
     * @return mixed
     */
    public function handle(TestResult $testResult, callable $next)
    {
        $data = $testResult->request ? $testResult->request->toArray() : [];
        $validator = Validator::make(
            $data,
            (array) $this->testScript->rules,
            (array) $this->testScript->messages
        );
        $testExecution = $testResult->testExecutions()->make([
            'name' => __('Request: :name', ['name' => $this->testScript->name]),
            'expected' => $this->testScript->rules,
        ]);

        foreach (array_keys($this->testScript->rules) as $attribute) {
            $testExecution->actual = array_merge((array) $testExecution->actual, ValidationData::initializeAndGatherData($attribute, $data));
        }

        try {
            $validator->validate();
            $testResult->total++;
            $testResult->passed++;
            $testExecution->successful();
        } catch (ValidationException $e) {
            $testResult->total++;
            $testResult->failures++;
            $testExecution->unsuccessful(implode(PHP_EOL, $validator->errors()->all()));
        } catch (Throwable $e) {
            $testResult->total++;
            $testResult->failures++;
            $testExecution->unsuccessful($e->getMessage());
        }

        return $next($testResult);
    }
}
