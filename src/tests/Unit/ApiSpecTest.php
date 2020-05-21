<?php

namespace Tests\Unit;

use App\Models\ApiSpec;
use cebe\openapi\spec\OpenApi;
use Tests\TestCase;

class ApiSpecTest extends TestCase
{
    /**
     * Test ApiSpec with valid data.
     *
     * @return void
     */
    public function testApiSpecValidData()
    {
        $apiSpec = factory(ApiSpec::class)->make();
        $this->assertValidationPasses($apiSpec->attributesToArray(), [
            'name' => ['required', 'string', 'max:255'],
            'openapi' => ['required'],
            'description' => ['required', 'string'],
        ]);
    }

    /**
     * Test ApiSpec OpenAPi instance.
     *
     * @return void
     */
    public function testApiSpecOpenApi()
    {
        $apiSpec = factory(ApiSpec::class)->create();
        $this->assertInstanceOf(OpenApi::class, $apiSpec->openapi);
    }

    /**
     * Test ApiSpec delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testApiSpecDelete()
    {
        $apiSpec = factory(ApiSpec::class)->create();
        $apiSpec->delete();
        $this->assertDeleted($apiSpec);
    }
}
