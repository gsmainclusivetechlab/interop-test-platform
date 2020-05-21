<?php

namespace Tests\Unit;

use App\Models\UseCase;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    /**
     * Test UseCase with valid data.
     *
     * @return void
     */
    public function testUseCaseValidData()
    {
        $useCase = factory(UseCase::class)->make();
        $this->assertValidationPasses($useCase->attributesToArray(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
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
