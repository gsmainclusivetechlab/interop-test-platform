<?php

namespace Tests\Unit\Controllers\Admin;

use App\Models\UseCase;
use App\Models\User;
use Tests\TestCase;

class UseCaseControllerTest extends TestCase
{
    public function testUseCaseControllerStore()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $response = $this->actingAs($user)->post(
            route('admin.use-cases.store'),
            [
                'name' => 'Use Case Name',
                'description' => 'Description',
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.use-cases.index'), ['success']);
    }

    public function testUseCaseControllerUpdate()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $useCase = factory(UseCase::class)->create();
        $response = $this->actingAs($user)->put(
            route('admin.use-cases.update', [
                'use_case' => $useCase,
            ]),
            [
                'name' => 'UseCase Updated Name',
                'description' => 'Updated Description',
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.use-cases.index'), [
            'success' => 'Use case updated successfully',
        ]);
    }
}
