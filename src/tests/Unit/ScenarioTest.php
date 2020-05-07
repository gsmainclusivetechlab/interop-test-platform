<?php

namespace Tests\Unit;

use App\Models\Scenario;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ScenarioTest extends TestCase
{
    /**
     * Test Scenario creating with valid data.
     *
     * @return void
     */
    public function testScenarioStoreValidData()
    {
        $scenario = factory(Scenario::class)->create();
        $this->assertInstanceOf(Scenario::class, $scenario);
    }

    /**
     * Test Scenario creating with invalid data.
     *
     * @return void
     */
    public function testScenarioStoreInvalidData()
    {
        $emptyScenario = factory(Scenario::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyScenario->attributesToArray(), self::rules())->passes());

        $validationFailedScenario = factory(Scenario::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedScenario->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test Scenario updating with valid data.
     *
     * @return void
     */
    public function testScenarioUpdateValidData()
    {
        $scenario = factory(Scenario::class)->create();
        $this->assertTrue($scenario->update(factory(Scenario::class)->make()->attributesToArray()));
    }

    /**
     * Test Scenario updating with invalid data.
     *
     * @return void
     */
    public function testScenarioUpdateInvalidData()
    {
        $scenarioWithEmptyData = factory(Scenario::class)->create();
        $scenarioWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($scenarioWithEmptyData->attributesToArray(), self::rules())->passes());

        $scenarioWithInvalidData = factory(Scenario::class)->create();
        $scenarioWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($scenarioWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string']
        ];
    }

    /**
     * Scenario Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'name' => null,
            'description' => null,
        ];
    }

    /**
     * Scenario Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'name' => Str::random(500),
            'description' => 125,
        ];
    }
}
