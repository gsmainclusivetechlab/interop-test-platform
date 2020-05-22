<?php

namespace Tests\Unit;

use App\Models\TestResult;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestResultTest extends TestCase
{
    public function setUp(): void
    {
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    /**
     * Test TestResult creating with valid data.
     *
     * @return void
     */
    public function testTestResultStoreValidData()
    {
        $testResult = factory(TestResult::class)->create();
        $this->assertInstanceOf(TestResult::class, $testResult);
    }

    /**
     * Test TestResult creating with invalid data.
     *
     * @return void
     */
    public function testTestResultStoreInvalidData()
    {
        $emptyTestResult = factory(TestResult::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestResult->attributesToArray(), self::rules())->passes());

        $validationFailedTestResult = factory(TestResult::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestResult->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestResult updating with valid data.
     *
     * @return void
     */
    public function testTestResultUpdateValidData()
    {
        $testResult = factory(TestResult::class)->create();
        $testResult->setRawAttributes(factory(TestResult::class)->make()->attributesToArray());
        $this->assertTrue($testResult->save());
    }

    /**
     * Test TestResult updating with invalid data.
     *
     * @return void
     */
    public function testTestResultUpdateInvalidData()
    {
        $testResultWithEmptyData = factory(TestResult::class)->create();
        $testResultWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testResultWithEmptyData->attributesToArray(), self::rules())->passes());

        $testResultWithInvalidData = factory(TestResult::class)->create();
        $testResultWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testResultWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestResult delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestResultDelete()
    {
        $testResult = factory(TestResult::class)->create();
        $testResult->delete();
        $this->assertDeleted($testResult->getTable(), $testResult->attributesToArray());
    }

    /**
     * Test TestResult successful.
     *
     * @return void
     */
    public function testTestResultGetSuccessfulAttribute()
    {
        $testResult = factory(TestResult::class)->make([
            'status' => TestResult::STATUS_INCOMPLETE,
        ]);
        $this->assertFalse($testResult->successful);

        $testResult->status = TestResult::STATUS_FAIL;
        $this->assertFalse($testResult->successful);

        $testResult->status = TestResult::STATUS_PASS;
        $this->assertTrue($testResult->successful);
    }

    /**
     * Test TestResult pass.
     *
     * @return void
     */
    public function testTestResultPass()
    {
        $testResult = factory(TestResult::class)->make([
            'status' => TestResult::STATUS_INCOMPLETE,
        ]);
        $this->assertFalse($testResult->status === TestResult::STATUS_PASS);

        $testResult->pass();
        $this->assertTrue($testResult->status === TestResult::STATUS_PASS);
    }

    /**
     * Test TestResult fail.
     *
     * @return void
     */
    public function testTestResultFail()
    {
        $testResult = factory(TestResult::class)->make([
            'status' => TestResult::STATUS_INCOMPLETE,
        ]);
        $this->assertFalse($testResult->status === TestResult::STATUS_FAIL);

        $testResult->fail();
        $this->assertTrue($testResult->status === TestResult::STATUS_FAIL);
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'test_run_id' => ['required', 'exists:test_runs,id'],
            'test_step_id' => ['required', 'exists:test_steps,id'],
            'request' => ['string'],
            'response' => ['string'],
            'exception' => ['string'],
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
            'test_run_id' => null,
            'test_step_id' => null,
            'request' => null,
            'response' => null,
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
            'test_run_id' => Str::random(500),
            'test_step_id' => Str::random(500),
            'request' => 125,
            'response' => 125,
            'exception' => 125,
            'status' => Str::random(500),
        ];
    }
}
