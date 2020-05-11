<?php

namespace Tests\Unit;

use App\Models\TestStep;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestStepTest extends TestCase
{
    /**
     * Test TestStep creating with valid data.
     *
     * @return void
     */
    public function testTestStepStoreValidData()
    {
        $testStep = factory(TestStep::class)->create();
        $this->assertInstanceOf(TestStep::class, $testStep);
    }

    /**
     * Test TestStep creating with invalid data.
     *
     * @return void
     */
    public function testTestStepStoreInvalidData()
    {
        $emptyTestStep = factory(TestStep::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestStep->attributesToArray(), self::rules())->passes());

        $validationFailedTestStep = factory(TestStep::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestStep->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestStep updating with valid data.
     *
     * @return void
     */
    public function testTestStepUpdateValidData()
    {
        $testStep = factory(TestStep::class)->create();
        $testStep->setRawAttributes(factory(TestStep::class)->make()->attributesToArray());
        $this->assertTrue($testStep->save());
    }

    /**
     * Test TestStep updating with invalid data.
     *
     * @return void
     */
    public function testTestStepUpdateInvalidData()
    {
        $testStepWithEmptyData = factory(TestStep::class)->create();
        $testStepWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testStepWithEmptyData->attributesToArray(), self::rules())->passes());

        $testStepWithInvalidData = factory(TestStep::class)->create();
        $testStepWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testStepWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestStep delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestStepDelete()
    {
        $testStep = factory(TestStep::class)->create();
        $testStep->delete();
        $this->assertDeleted($testStep->getTable(), $testStep->attributesToArray());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'test_case_id' => ['required', 'exists:test_cases,id'],
            'source_id' => ['required', 'exists:components,id'],
            'target_id' => ['required', 'exists:components,id'],
            'api_scheme_id' => ['exists:api_schemes,id'],
            'name' => ['required', 'string', 'max:255'],
            'request' => ['string'],
            'response' => ['string'],
            'position' => ['required', 'integer'],
        ];
    }

    /**
     * TestStep Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'test_case_id' => null,
            'source_id' => null,
            'target_id' => null,
            'api_scheme_id' => null,
            'name' => null,
            'request' => null,
            'response' => null,
            'position' => null,
        ];
    }

    /**
     * TestStep Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'test_case_id' => Str::random(500),
            'source_id' => Str::random(500),
            'target_id' => Str::random(500),
            'api_scheme_id' => Str::random(500),
            'name' => Str::random(500),
            'request' => 125,
            'response' => 125,
            'position' => Str::random(500),
        ];
    }
}
