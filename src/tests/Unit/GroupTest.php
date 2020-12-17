<?php

namespace Tests\Unit;

use App\Models\Group;
use Tests\TestCase;

class GroupTest extends TestCase
{
    /**
     * Test Group store.
     *
     * @return void
     */
    public function testGroupStore()
    {
        $group = factory(Group::class)->make();
        $this->assertValidationPasses($group->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'domain' => ['string'],
        ]);
        $this->assertTrue($group->save());
    }

    /**
     * Test Group delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testGroupDelete()
    {
        $group = factory(Group::class)->create();
        $group->delete();
        $this->assertDeleted($group);
    }
}
