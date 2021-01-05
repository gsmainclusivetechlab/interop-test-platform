<?php

namespace Tests\Unit;

use App\Models\TestExecution;
use Tests\TestCase;

class TestExecutionTest extends TestCase
{
    /**
     * Test Execution store.
     *
     * @return void
     */
    public function testTestExecutionStore()
    {
        $testExecution = factory(TestExecution::class)->make();
        $this->assertValidationPasses($testExecution->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'actual' => ['string'],
            'expected' => ['string'],
        ]);
        $this->assertTrue($testExecution->save());
    }

    /**
     * Test Execution delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestExecutionDelete()
    {
        $testExecution = factory(TestExecution::class)->create();
        $testExecution->delete();
        $this->assertDeleted($testExecution);
    }
}
