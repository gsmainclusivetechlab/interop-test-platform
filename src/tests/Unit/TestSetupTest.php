<?php

namespace Tests\Unit;

use App\Models\TestSetup;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class TestSetupTest extends TestCase
{
    /**
     * Test TestSetup store.
     *
     * @return void
     */
    public function testTestSetupStore()
    {
        $testSetup = factory(TestSetup::class)->make();
        $this->assertValidationPasses($testSetup->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in([TestSetup::TYPE_RESPONSE, TestSetup::TYPE_REQUEST])],
            'values' => ['required'],
        ]);
        $this->assertTrue($testSetup->save());
    }

    /**
     * Test TestSetup delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestSetupDelete()
    {
        $testSetup = factory(TestSetup::class)->create();
        $testSetup->delete();
        $this->assertDeleted($testSetup);
    }
}
