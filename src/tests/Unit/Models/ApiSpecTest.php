<?php

namespace Tests\Unit\Models;

use App\Models\ApiSpec;
use cebe\openapi\spec\OpenApi;
use Tests\TestCase;

class ApiSpecTest extends TestCase
{
    /**
     * Test ApiSpec store.
     *
     * @return void
     */
    public function testApiSpecStore()
    {
        $apiSpec = factory(ApiSpec::class)->make();
        $this->assertValidationPasses($apiSpec->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'openapi' => ['required'],
            'description' => ['required', 'string'],
        ]);
        $this->assertTrue($apiSpec->save());
    }

    /**
     * Test ApiSpec contains OpenAPi instance.
     *
     * @return void
     */
    public function testApiSpecContainsOpenApi()
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
