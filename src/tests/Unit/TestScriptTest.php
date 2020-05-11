<?php

namespace Tests\Unit;

use App\Models\TestScript;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestScriptTest extends TestCase
{
    /**
     * Test TestScript creating with valid data.
     *
     * @return void
     */
    public function testTestScriptStoreValidData()
    {
        $testScript = factory(TestScript::class)->create();
        $this->assertInstanceOf(TestScript::class, $testScript);
    }

    /**
     * Test TestScript creating with invalid data.
     *
     * @return void
     */
    public function testTestScriptStoreInvalidData()
    {
        $emptyTestScript = factory(TestScript::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestScript->attributesToArray(), self::rules())->passes());

        $validationFailedTestScript = factory(TestScript::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestScript->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestScript updating with valid data.
     *
     * @return void
     */
    public function testTestScriptUpdateValidData()
    {
        $testScript = factory(TestScript::class)->create();
        $testScript->setRawAttributes(factory(TestScript::class)->make()->attributesToArray());
        $this->assertTrue($testScript->save());
    }

    /**
     * Test TestScript updating with invalid data.
     *
     * @return void
     */
    public function testTestScriptUpdateInvalidData()
    {
        $testScriptWithEmptyData = factory(TestScript::class)->create();
        $testScriptWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testScriptWithEmptyData->attributesToArray(), self::rules())->passes());

        $testScriptWithInvalidData = factory(TestScript::class)->create();
        $testScriptWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testScriptWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestScript delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestScriptDelete()
    {
        $testScript = factory(TestScript::class)->create();
        $testScript->delete();
        $this->assertDeleted($testScript->getTable(), $testScript->attributesToArray());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'test_step_id' => ['required', 'exists:test_steps,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'rules' => ['required', 'string'],
            'messages' => ['string'],
            'position' => ['required', 'integer'],
        ];
    }

    /**
     * TestResult Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'test_step_id' => null,
            'name' => null,
            'type' => null,
            'rules' => null,
            'messages' => null,
            'position' => null,
        ];
    }

    /**
     * TestResult Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'test_step_id' => Str::random(500),
            'name' => Str::random(500),
            'type' => 125,
            'rules' => 125,
            'messages' => 125,
            'position' => Str::random(500),
        ];
    }
}
