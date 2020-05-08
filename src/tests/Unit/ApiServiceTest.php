<?php

namespace Tests\Unit;

use App\Models\ApiService;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiServiceTest extends TestCase
{
    /**
     * Test ApiService creating with valid data.
     *
     * @return void
     */
    public function testApiServiceStoreValidData()
    {
        $apiService = factory(ApiService::class)->create();
        $this->assertInstanceOf(ApiService::class, $apiService);
    }

    /**
     * Test ApiService creating with invalid data.
     *
     * @return void
     */
    public function testApiServiceStoreInvalidData()
    {
        $emptyApiService = factory(ApiService::class)->make(self::emptyData());
        $this->assertFalse(Validator::make($emptyApiService->attributesToArray(), self::rules())->passes());

        $validationFailedApiService = factory(ApiService::class)->make(self::invalidData());
        $this->assertFalse(Validator::make($validationFailedApiService->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test ApiService updating with valid data.
     *
     * @return void
     */
    public function testApiServiceUpdateValidData()
    {
        $apiService = factory(ApiService::class)->create();
        $this->assertTrue($apiService->update(factory(ApiService::class)->make()->attributesToArray()));
    }

    /**
     * Test ApiService updating with invalid data.
     *
     * @return void
     */
    public function testApiServiceUpdateInvalidData()
    {
        $apiServiceWithEmptyData = factory(ApiService::class)->create();
        $apiServiceWithEmptyData->setRawAttributes(self::emptyData());
        $this->assertFalse(Validator::make($apiServiceWithEmptyData->attributesToArray(), self::rules())->passes());

        $apiServiceWithInvalidData = factory(ApiService::class)->create();
        $apiServiceWithInvalidData->setRawAttributes(self::invalidData());
        $this->assertFalse(Validator::make($apiServiceWithInvalidData->attributesToArray(), self::rules())->passes());
    }

    /**
     * Test ApiService delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testApiServiceDelete()
    {
        $apiService = factory(ApiService::class)->create();
        $apiService->delete();
        $this->assertDeleted($apiService->getTable(), $apiService->attributesToArray());
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
            'base_url' => ['required', 'string', 'max:255'],
            'description' => ['string'],
        ];
    }

    /**
     * ApiService Empty Data.
     *
     * @return array
     */
    protected static function emptyData()
    {
        return [
            'name' => null,
            'base_url' => null,
            'description' => null,
        ];
    }

    /**
     * ApiService Invalid Data.
     *
     * @return array
     */
    protected static function invalidData()
    {
        return [
            'name' => Str::random(500),
            'base_url' => Str::random(500),
            'description' => 125,
        ];
    }
}
