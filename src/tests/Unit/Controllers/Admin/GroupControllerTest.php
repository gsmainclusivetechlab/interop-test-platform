<?php

namespace Tests\Unit\Controllers\Admin;

use App\Models\Group;
use App\Models\User;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    public function testGroupControllerStore()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $response = $this->actingAs($user)->post(route('admin.groups.store'), [
            'name' => 'Group Name',
            'domain' => 'Domain name',
            'description' => 'Description',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.groups.index'), ['success']);
    }

    public function testGroupControllerUpdate()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $group = factory(Group::class)->create();
        $response = $this->actingAs($user)->put(
            route('admin.groups.update', [
                'group' => $group,
            ]),
            [
                'name' => 'Group Updated Name',
                'domain' => 'Domain Updated name',
                'description' => 'Updated Description',
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.groups.index'), [
            'success' => 'Group updated successfully',
        ]);
    }
}
