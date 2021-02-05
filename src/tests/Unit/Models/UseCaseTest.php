<?php

namespace Tests\Unit\Models;

use App\Models\UseCase;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    /**
     * Test UseCase store.
     *
     * @return void
     */
    public function testUseCaseStore()
    {
        $useCase = factory(UseCase::class)->make();
        $this->assertValidationPasses($useCase->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        $this->assertTrue($useCase->save());
    }

    /**
     * Test UseCase delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testUseCaseDelete()
    {
        $useCase = factory(UseCase::class)->create();
        $useCase->delete();
        $this->assertDeleted($useCase);
    }
}
