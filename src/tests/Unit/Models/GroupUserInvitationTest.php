<?php

namespace Tests\Unit\Models;

use App\Models\GroupUserInvitation;
use Tests\TestCase;

class GroupUserInvitationTest extends TestCase
{
    /**
     * Test Group User Invitation store.
     *
     * @return void
     */
    public function testGroupUserInvitationStore()
    {
        $groupUserInvitation = factory(GroupUserInvitation::class)->make();
        $this->assertValidationPasses($groupUserInvitation->getAttributes(), [
            'email' => ['required', 'string', 'max:255'],
            'expired_at' => ['required', 'date']
        ]);
        $this->assertTrue($groupUserInvitation->save());
    }

    /**
     * Test Group User Invitation delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testGroupUserInvitationDelete()
    {
        $groupUserInvitation = factory(GroupUserInvitation::class)->create();
        $groupUserInvitation->delete();
        $this->assertDeleted($groupUserInvitation);
    }
}
