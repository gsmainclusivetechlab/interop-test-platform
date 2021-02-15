<?php

namespace Tests\Unit\Models;

use App\Models\Component;
use Tests\TestCase;

class ComponentTest extends TestCase
{
    /**
     * Test Component store.
     *
     * @return void
     */
    public function testComponentStore()
    {
        $component = factory(Component::class)->make();
        $this->assertValidationPasses($component->getAttributes(), [
            'slug' => ['required', 'string', 'max:255'],
        ]);
        $this->assertTrue($component->save());
    }

    /**
     * Test Component delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testComponentDelete()
    {
        $component = factory(Component::class)->create();
        $component->delete();
        $this->assertDeleted($component);
    }
}
