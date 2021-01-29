<?php

namespace Tests\Unit\Controllers;

use App\Models\Component;
use App\Models\Group;
use App\Models\GroupEnvironment;
use App\Models\Session;
use App\Models\User;
use Tests\TestCase;

class GroupEnvironmentControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGroupEnvironmentControllerStore()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $group = factory(Group::class)
            ->create()
            ->getKey();
        $response = $this->actingAs($user)
            ->withSession([
                'group' => $group,
            ])
            ->post(
                route('groups.environments.store', [
                    'group' => $group,
                ]),
                [
                    'name' => 'Environment name',
                    'variables' => [
                        'test_variable' => 'test_value',
                    ],
                ]
            );

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('groups.environments.index', $group));
    }

    public function testGroupEnvironmentControllerUpdate()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $group = factory(Group::class)
            ->create()
            ->getKey();
        $environment = factory(GroupEnvironment::class)->create();
        $response = $this->actingAs($user)->put(
            route('groups.environments.update', [
                'group' => $group,
                'environment' => $environment,
            ]),
            [
                'name' => 'Environment name',
                'variables' => [
                    'test_variable' => 'test_value',
                ],
            ]
        );

        $response->assertSuccessful();
        $response->assertSessionHasNoErrors();
    }
}
