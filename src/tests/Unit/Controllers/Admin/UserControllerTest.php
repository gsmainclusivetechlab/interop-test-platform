<?php

namespace Tests\Unit\Controllers\Admin;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testUserControllerPromoteRole()
    {
        $adminUser = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $response = $this->actingAs($adminUser)->put(
            route('admin.users.promote-role', [
                'user' => $user,
                'role' => User::ROLE_TEST_CASE_CREATOR,
            ])
        );

        $response->assertSessionHasNoErrors();
        $response->assertSuccessful();
    }
}
