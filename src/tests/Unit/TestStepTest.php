<?php

namespace Tests\Unit;

use App\Models\TestStep;
use Tests\TestCase;

class TestStepTest extends TestCase
{
    /**
     * Test TestStep store.
     *
     * @return void
     */
    public function testTestStepStore()
    {
        $testStep = factory(TestStep::class)->make();
        $this->assertValidationPasses($testStep->getAttributes(), [
            'path' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'pattern' => ['required', 'string', 'max:255'],
            'trigger' => ['required'],
            'request' => ['required'],
            'response' => ['required'],
        ]);
        $this->assertTrue($testStep->save());
    }

    /**
     * Test TestStep delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestStepDelete()
    {
        $testStep = factory(TestStep::class)->create();
        $testStep->delete();
        $this->assertDeleted($testStep);
    }
}
