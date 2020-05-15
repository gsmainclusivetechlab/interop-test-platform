<?php

namespace Tests\Unit;

use App\Models\TestCase;
use Tests\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestCaseTest extends BaseTestCase
{
    /**
     * Test TestCase creating with valid data.
     *
     * @return void
     */
    public function testTestCaseStoreValidData()
    {
        $testCase = factory(TestCase::class)->create();
        $this->assertInstanceOf(TestCase::class, $testCase);
    }

    /**
     * Test TestCase creating with invalid data.
     *
     * @return void
     */
    public function testTestCaseStoreInvalidData()
    {
        $emptyTestCase = factory(TestCase::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestCase->attributesToArray(), self::rules())->passes());

        $validationFailedTestCase = factory(TestCase::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestCase->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestCase updating with valid data.
     *
     * @return void
     */
    public function testTestCaseUpdateValidData()
    {
        $testCase = factory(TestCase::class)->create();
        $this->assertTrue($testCase->update(factory(TestCase::class)->make()->attributesToArray()));
    }

    /**
     * Test TestCase updating with invalid data.
     *
     * @return void
     */
    public function testTestCaseUpdateInvalidData()
    {
        $testCaseWithEmptyData = factory(TestCase::class)->create();
        $testCaseWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testCaseWithEmptyData->attributesToArray(), self::rules())->passes());

        $testCaseWithInvalidData = factory(TestCase::class)->create();
        $testCaseWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testCaseWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestCase delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestCaseDelete()
    {
        $testCase = factory(TestCase::class)->create();
        $testCase->delete();
        $this->assertDeleted($testCase->getTable(), $testCase->attributesToArray());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'uuid' => ['required', 'string', 'max:36'],
            'use_case_id' => ['required', 'exists:use_cases,id'],
            'name' => ['required', 'string', 'max:255'],
            'behavior' => ['required', 'string', 'max:255'],
            'precondition' => ['string'],
            'description' => ['string'],
        ];
    }

    /**
     * TestCase Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'uuid' => null,
            'use_case_id' => null,
            'name' => null,
            'behavior' => null,
            'precondition' => null,
            'description' => null,
        ];
    }

    /**
     * TestCase Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'uuid' => Str::random(500),
            'use_case_id' => Str::random(500),
            'name' => Str::random(500),
            'behavior' => Str::random(500),
            'precondition' => 125,
            'description' => 125,
        ];
    }
}
