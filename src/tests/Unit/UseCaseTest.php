<?php

namespace Tests\Unit;

use App\Models\Scenario;
use App\Models\UseCase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UseCaseTest extends TestCase
{
    /**
     * Test UseCase creating with valid data.
     *
     * @return void
     */
    public function testUseCaseStoreValidData()
    {
        factory(Scenario::class)->create();
        $useCase = factory(UseCase::class)->create();
        $this->assertInstanceOf(UseCase::class, $useCase);
    }

    /**
     * Test UseCase creating with invalid data.
     *
     * @return void
     */
    public function testUseCaseStoreInvalidData()
    {
        $emptyUseCase = factory(UseCase::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyUseCase->attributesToArray(), self::rules())->passes());

        $validationFailedUseCase = factory(UseCase::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedUseCase->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test UseCase updating with valid data.
     *
     * @return void
     */
    public function testUseCaseUpdateValidData()
    {
        factory(Scenario::class)->create();
        $useCase = factory(UseCase::class)->create();
        $this->assertTrue($useCase->update(factory(UseCase::class)->make()->attributesToArray()));
    }

    /**
     * Test UseCase updating with invalid data.
     *
     * @return void
     */
    public function testUseCaseUpdateInvalidData()
    {
        factory(Scenario::class)->create();

        $useCaseWithEmptyData = factory(UseCase::class)->create();
        $useCaseWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($useCaseWithEmptyData->attributesToArray(), self::rules())->passes());

        $useCaseWithInvalidData = factory(UseCase::class)->create();
        $useCaseWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($useCaseWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Database validation rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'scenario_id' => ['required', 'exists:scenarios,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
        ];
    }

    /**
     * User Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'scenario_id' => null,
            'name' => null,
            'description' => null,
        ];
    }

    /**
     * User Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'scenario_id' => Str::random(500),
            'name' => Str::random(500),
            'description' => 125,
        ];
    }
}
