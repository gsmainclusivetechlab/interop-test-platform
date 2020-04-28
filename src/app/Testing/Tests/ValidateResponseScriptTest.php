<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Models\TestResult;
use App\Models\TestScript;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;

class ValidateResponseScriptTest
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
        $data = $testResult->response->toArray();
        $validator = Validator::make(
            $data,
            (array) $this->testScript->rules,
            (array) $this->testScript->messages,
            (array) $this->testScript->attributes
        );
        $testExecution = $testResult->testExecutions()->make([
            'name' => __('Response: :name', ['name' => $this->testScript->name]),
            'expected' => $this->testScript->rules,
        ]);

        foreach (array_keys($this->testScript->rules) as $attribute) {
            $testExecution->actual = array_merge((array) $testExecution->actual, ValidationData::initializeAndGatherData($attribute, $data));
        }

        if ($validator->passes()) {
            $testExecution->successful();
        } else {
            $testExecution->unsuccessful(implode(PHP_EOL, $validator->errors()->all()));
        }

        return $next($testResult);
    }
}
