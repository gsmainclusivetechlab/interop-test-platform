<?php

namespace Tests\Unit\Models;

use App\Models\GroupEnvironment;
use Tests\TestCase;

class GroupEnvironmentTest extends TestCase
{
    /**
     * Test Group Environment store.
     *
     * @return void
     */
    public function testGroupEnvironmentStore()
    {
        $groupEnvironment = factory(GroupEnvironment::class)->make();
        $this->assertValidationPasses($groupEnvironment->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'variables' => ['required'],
        ]);
        $this->assertTrue($groupEnvironment->save());
    }

    /**
     * Test Group Environment delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testGroupEnvironmentDelete()
    {
        $groupEnvironment = factory(GroupEnvironment::class)->create();
        $groupEnvironment->delete();
        $this->assertDeleted($groupEnvironment);
    }
}
