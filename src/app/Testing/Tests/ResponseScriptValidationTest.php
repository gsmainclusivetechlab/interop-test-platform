<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Response;
use App\Models\TestScript;
use App\Testing\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\AssertionFailedError;

class ResponseScriptValidationTest extends TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var TestScript
     */
    protected $testScript;

    /**
     * @param Response $response
     * @param TestScript $testScript
     */
    public function __construct(Response $response, TestScript $testScript)
    {
        $this->response = $response;
        $this->testScript = $testScript;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = Validator::make(
            $this->response->toArray(),
            (array) $this->testScript->rules,
            (array) $this->testScript->messages
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
        return __('Response: :name', ['name' => $this->testScript->name]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return tap([], function (&$actual) {
            $expected = $this->getExpected();
            $data = $this->response->toArray();

            foreach (array_keys($expected) as $attribute) {
                $actual = array_merge($actual, ValidationData::initializeAndGatherData($attribute, $data));
            }
        });
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        return (array) $this->testScript->rules;
    }
}
