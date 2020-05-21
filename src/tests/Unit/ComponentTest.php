<?php

namespace Tests\Unit;

use App\Models\Component;
use Tests\TestCase;

class ComponentTest extends TestCase
{
    /**
     * Test Component with valid data.
     *
     * @return void
     */
    public function testComponentValidData()
    {
        $component = factory(Component::class)->make();
        $this->assertValidationPasses($component->attributesToArray(), [
            'name' => ['required', 'string', 'max:255'],
            'base_url' => ['required', 'url'],
            'description' => ['required', 'string'],
        ]);
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
