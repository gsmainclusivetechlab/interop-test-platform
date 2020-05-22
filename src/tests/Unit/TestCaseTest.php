<?php

namespace Tests\Unit;

use App\Models\TestCase;
use Illuminate\Validation\Rule;
use Tests\TestCase as BaseTestCase;

class TestCaseTest extends BaseTestCase
{
    /**
     * Test TestCase store.
     *
     * @return void
     */
    public function testTestCaseStore()
    {
        $testCase = factory(TestCase::class)->make();
        $this->assertValidationPasses($testCase->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'behavior' => ['required', Rule::in([TestCase::BEHAVIOR_NEGATIVE, TestCase::BEHAVIOR_POSITIVE])],
            'precondition' => ['string'],
            'description' => ['string'],
        ]);
        $this->assertTrue($testCase->save());
    }

    /**
     * Test TestCase delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestCaseDelete()
    {
        $testCase = factory(TestCase::class)->create();
        $testCase->delete();
        $this->assertDeleted($testCase);
    }
}
