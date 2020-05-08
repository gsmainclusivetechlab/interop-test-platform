<?php

namespace Tests\Unit;

use App\Models\TestSetup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TestSetupTest extends TestCase
{
    /**
     * Test TestSetup creating with valid data.
     *
     * @return void
     */
    public function testTestSetupStoreValidData()
    {
        $testSetup = factory(TestSetup::class)->create();
        $this->assertInstanceOf(TestSetup::class, $testSetup);
    }

    /**
     * Test TestSetup creating with invalid data.
     *
     * @return void
     */
    public function testTestSetupStoreInvalidData()
    {
        $emptyTestSetup = factory(TestSetup::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyTestSetup->attributesToArray(), self::rules())->passes());

        $validationFailedTestSetup = factory(TestSetup::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedTestSetup->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestSetup updating with valid data.
     *
     * @return void
     */
    public function testTestSetupUpdateValidData()
    {
        $testSetup = factory(TestSetup::class)->create();
        $testSetup->setRawAttributes(factory(TestSetup::class)->make()->attributesToArray());
        $this->assertTrue($testSetup->save());
    }

    /**
     * Test TestSetup updating with invalid data.
     *
     * @return void
     */
    public function testTestSetupUpdateInvalidData()
    {
        $testSetupWithEmptyData = factory(TestSetup::class)->create();
        $testSetupWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($testSetupWithEmptyData->attributesToArray(), self::rules())->passes());

        $testSetupWithInvalidData = factory(TestSetup::class)->create();
        $testSetupWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($testSetupWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test TestSetup delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestSetupDelete()
    {
        $testSetup = factory(TestSetup::class)->create();
        $testSetup->delete();
        $this->assertDeleted($testSetup->getTable(), $testSetup->attributesToArray());
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
            'values' => ['required', 'string'],
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
            'values' => null,
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
            'values' => 125,
            'position' => Str::random(500),
        ];
    }
}
