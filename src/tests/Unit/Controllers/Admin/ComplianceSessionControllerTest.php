<?php

namespace Tests\Unit\Controllers\Admin;

use App\Models\Session;
use App\Models\User;
use Tests\TestCase;

class ComplianceSessionControllerTest extends TestCase
{
    public function testComplianceSessionUpdateInVerification()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $session = factory(Session::class)->create([
            'status' => Session::STATUS_IN_VERIFICATION,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['session' => $session])
            ->put(
                route('admin.compliance-sessions.update', [
                    'session' => $session->getKey(),
                ]),
                [
                    'reason' => 'update reason',
                    'status' => 'approved',
                ]
            );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }

    public function testComplianceSessionUpdateNotInVerification()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $session = factory(Session::class)->create([
            'status' => Session::STATUS_DECLINED,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['session' => $session])
            ->put(
                route('admin.compliance-sessions.update', [
                    'session' => $session->getKey(),
                ])
            );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('sessions.show', $session));
    }
}
