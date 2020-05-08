<?php

namespace Tests\Unit;

use App\Models\TestExecution;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestExecutionTest extends TestCase
{
    /**
     * Test TestExecution creating with valid data.
     *
     * @return void
     */
    public function testTestExecutionStoreValidData()
    {
        $testExecution = factory(TestExecution::class)->create();
        $this->assertInstanceOf(TestExecution::class, $testExecution);
    }

    /**
     * Test TestExecution creating with invalid data.
     *
     * @return void
     */
    public function testTestExecutionStoreInvalidData()
    {
        $emptyTestExecution = factory(TestExecution::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestExecution->attributesToArray(), self::rules())->passes());

        $validationFailedTestExecution = factory(TestExecution::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestExecution->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestExecution updating with valid data.
     *
     * @return void
     */
    public function testTestExecutionUpdateValidData()
    {
        $testExecution = factory(TestExecution::class)->create();
        $testExecution->setRawAttributes(factory(TestExecution::class)->make()->attributesToArray());
        $this->assertTrue($testExecution->save());
    }

    /**
     * Test TestExecution updating with invalid data.
     *
     * @return void
     */
    public function testTestExecutionUpdateInvalidData()
    {
        $testExecutionWithEmptyData = factory(TestExecution::class)->create();
        $testExecutionWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testExecutionWithEmptyData->attributesToArray(), self::rules())->passes());

        $testExecutionWithInvalidData = factory(TestExecution::class)->create();
        $testExecutionWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testExecutionWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestExecution delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestExecutionDelete()
    {
        $testExecution = factory(TestExecution::class)->create();
        $testExecution->delete();
        $this->assertDeleted($testExecution->getTable(), $testExecution->attributesToArray());
    }

    /**
     * Test TestExecution successful.
     *
     * @return void
     */
    public function testTestExecutionGetSuccessfulAttribute()
    {
        $testExecution = factory(TestExecution::class)->make([
            'status' => TestExecution::STATUS_FAIL,
        ]);
        $this->assertFalse($testExecution->successful);

        $testExecution->status = TestExecution::STATUS_PASS;
        $this->assertTrue($testExecution->successful);
    }

    /**
     * Test TestExecution pass.
     *
     * @return void
     */
    public function testTestExecutionPass()
    {
        $testExecution = factory(TestExecution::class)->make([
            'status' => TestExecution::STATUS_FAIL,
        ]);
        $this->assertFalse($testExecution->status === TestExecution::STATUS_PASS);

        $testExecution->pass();
        $this->assertTrue($testExecution->status === TestExecution::STATUS_PASS);
    }

    /**
     * Test TestExecution fail.
     *
     * @return void
     */
    public function testTestExecutionFail()
    {
        $testExecution = factory(TestExecution::class)->make([
            'status' => TestExecution::STATUS_PASS,
        ]);
        $this->assertFalse($testExecution->status === TestExecution::STATUS_FAIL);

        $testExecution->fail();
        $this->assertTrue($testExecution->status === TestExecution::STATUS_FAIL);
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'test_result_id' => ['required', 'exists:test_results,id'],
            'name' => ['required', 'string', 'max:255'],
            'actual' => ['string'],
            'expected' => ['string'],
            'exception' => ['string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
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
            'test_result_id' => null,
            'name' => null,
            'actual' => null,
            'expected' => null,
            'exception' => null,
            'status' => null,
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
            'test_result_id' => Str::random(500),
            'name' => Str::random(500),
            'actual' => 125,
            'expected' => 125,
            'exception' => 125,
            'status' => Str::random(500),
        ];
    }
}
