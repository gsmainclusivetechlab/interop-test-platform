<?php

namespace Tests\Unit;

use App\Models\ApiScheme;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiSchemeTest extends TestCase
{
    /**
     * Test ApiScheme creating with valid data.
     *
     * @return void
     */
    public function testApiSchemeStoreValidData()
    {
        $apiScheme = factory(ApiScheme::class)->create();
        $this->assertInstanceOf(ApiScheme::class, $apiScheme);
    }

    /**
     * Test ApiScheme creating with invalid data.
     *
     * @return void
     */
    public function testApiSchemeStoreInvalidData()
    {
        $emptyApiScheme = factory(ApiScheme::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyApiScheme->attributesToArray(), self::rules())->passes());

        $validationFailedApiScheme = factory(ApiScheme::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedApiScheme->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test ApiScheme updating with valid data.
     *
     * @return void
     */
    public function testApiSchemeUpdateValidData()
    {
        $apiScheme = factory(ApiScheme::class)->create();
        $this->assertTrue($apiScheme->update(factory(ApiScheme::class)->make()->attributesToArray()));
    }

    /**
     * Test ApiScheme updating with invalid data.
     *
     * @return void
     */
    public function testApiSchemeUpdateInvalidData()
    {
        $apiSchemeWithEmptyData = factory(ApiScheme::class)->create();
        $apiSchemeWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($apiSchemeWithEmptyData->attributesToArray(), self::rules())->passes());

        $apiSchemeWithInvalidData = factory(ApiScheme::class)->create();
        $apiSchemeWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($apiSchemeWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test ApiScheme delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testApiSchemeDelete()
    {
        $apiScheme = factory(ApiScheme::class)->create();
        $apiScheme->delete();
        $this->assertDeleted($apiScheme->getTable(), ['id' => $apiScheme->id]);
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
            'openapi' => ['required', 'string'],
            'description' => ['string'],
        ];
    }

    /**
     * ApiScheme Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'name' => null,
            'openapi' => null,
            'description' => null,
        ];
    }

    /**
     * ApiScheme Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'name' => Str::random(500),
            'openapi' => 125,
            'description' => 125,
        ];
    }
}
