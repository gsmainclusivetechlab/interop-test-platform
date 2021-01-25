<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Tests\TestCase;

class ApiSpecControllerTest extends TestCase
{

    public function testApiSpecControllerImport()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $file = '/database/seeds/openapis/mm.yaml';

        $response = $this->actingAs($user)->post(route('admin.api-specs.import.confirm', [
            'name' => 'api-spec test',
            'file' => $file
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.api-specs.index'));
        $response->assertStatus(302);
    }

}
