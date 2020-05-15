<?php

namespace Tests\Unit;

use App\Models\TestRun;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestRunTest extends TestCase
{
    /**
     * Test TestRun creating with valid data.
     *
     * @return void
     */
    public function testTestRunStoreValidData()
    {
        $testRun = factory(TestRun::class)->create();
        $this->assertInstanceOf(TestRun::class, $testRun);
    }

    /**
     * Test TestRun creating with invalid data.
     *
     * @return void
     */
    public function testTestRunStoreInvalidData()
    {
        $emptyTestRun = factory(TestRun::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestRun->attributesToArray(), self::rules())->passes());

        $validationFailedTestRun = factory(TestRun::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestRun->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestRun updating with valid data.
     *
     * @return void
     */
    public function testTestRunUpdateValidData()
    {
        $testRun = factory(TestRun::class)->create();
        $testRun->setRawAttributes(factory(TestRun::class)->make()->attributesToArray());
        $this->assertTrue($testRun->save());
    }

    /**
     * Test TestRun updating with invalid data.
     *
     * @return void
     */
    public function testTestRunUpdateInvalidData()
    {
        $testRunWithEmptyData = factory(TestRun::class)->create();
        $testRunWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testRunWithEmptyData->attributesToArray(), self::rules())->passes());

        $testRunWithInvalidData = factory(TestRun::class)->create();
        $testRunWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testRunWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestRun delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestRunDelete()
    {
        $testRun = factory(TestRun::class)->create();
        $testRun->delete();
        $this->assertDeleted($testRun->getTable(), $testRun->attributesToArray());
    }

    /**
     * Test TestRun complete.
     *
     * @return void
     */
    public function testTestRunComplete()
    {
        $testRun = factory(TestRun::class)->create();
        $this->assertEmpty($testRun->completed_at);
        $testRun->complete();
        $this->assertNotEmpty($testRun->completed_at);
    }

    /**
     * Test TestRun successful.
     *
     * @return void
     */
    public function testTestRunGetSuccessfulAttribute()
    {
        $total = 10;
        $testRun = factory(TestRun::class)->make([
            'total' => $total,
            'passed' => ++$total,
            'failures' => --$total,
        ]);
        $this->assertFalse($testRun->successful);
        $testRun->setRawAttributes([
            'total' => $total,
            'passed' => $total,
            'failures' => 0,
        ]);
        $this->assertTrue($testRun->successful);
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
            'session_id' => ['required', 'exists:sessions,id'],
            'test_case_id' => ['required', 'exists:test_cases,id'],
            'total' => ['required', 'integer'],
            'passed' => ['required', 'integer'],
            'failures' => ['required', 'integer'],
            'duration' => ['required', 'integer'],
        ];
    }

    /**
     * TestRun Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'uuid' => null,
            'session_id' => null,
            'test_case_id' => null,
            'total' => null,
            'passed' => null,
            'failures' => null,
            'duration' => null,
        ];
    }

    /**
     * TestRun Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'uuid' => Str::random(500),
            'session_id' => Str::random(500),
            'test_case_id' => Str::random(500),
            'total' => Str::random(500),
            'passed' => Str::random(500),
            'failures' => Str::random(500),
            'duration' => Str::random(500),
        ];
    }
}
