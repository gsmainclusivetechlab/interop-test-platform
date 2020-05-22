<?php

namespace Tests\Unit;

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
            'name' => ['required', 'string', 'max:255'],
            'base_url' => ['required', 'url'],
            'description' => ['required', 'string'],
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
