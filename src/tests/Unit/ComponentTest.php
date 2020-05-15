<?php

namespace Tests\Unit;

use App\Models\Component;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ComponentTest extends TestCase
{
    /**
     * Test Component creating with valid data.
     *
     * @return void
     */
    public function testComponentStoreValidData()
    {
        $component = factory(Component::class)->create();
        $this->assertInstanceOf(Component::class, $component);
    }

    /**
     * Test Component creating with invalid data.
     *
     * @return void
     */
    public function testComponentStoreInvalidData()
    {
        $emptyComponent = factory(Component::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyComponent->attributesToArray(), self::rules())->passes());

        $validationFailedComponent = factory(Component::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedComponent->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test Component updating with valid data.
     *
     * @return void
     */
    public function testComponentUpdateValidData()
    {
        $component = factory(Component::class)->create();
        $this->assertTrue($component->update(factory(Component::class)->make()->attributesToArray()));
    }

    /**
     * Test Component updating with invalid data.
     *
     * @return void
     */
    public function testComponentUpdateInvalidData()
    {
        $componentWithEmptyData = factory(Component::class)->create();
        $componentWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($componentWithEmptyData->attributesToArray(), self::rules())->passes());

        $componentWithInvalidData = factory(Component::class)->create();
        $componentWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($componentWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test Component delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testComponentDelete()
    {
        $component = factory(Component::class)->create();
        $component->delete();
        $this->assertDeleted($component->getTable(), $component->attributesToArray());
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
            'api_service_id' => ['required', 'exists:api_services,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'position' => ['integer'],
        ];
    }

    /**
     * Component Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'scenario_id' => null,
            'api_service_id' => null,
            'name' => null,
            'description' => null,
            'position' => null,
        ];
    }

    /**
     * Component Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'scenario_id' => Str::random(500),
            'api_service_id' => Str::random(500),
            'name' => Str::random(500),
            'description' => 125,
            'position' => Str::random(500),
        ];
    }
}
