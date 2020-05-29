<?php

namespace Tests\Unit;

use App\Models\TestScript;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class TestScriptTest extends TestCase
{
    /**
     * Test TestScript store.
     *
     * @return void
     */
    public function testTestScriptStoreValidData()
    {
        $testScript = factory(TestScript::class)->make();
        $this->assertValidationPasses($testScript->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'type' => [
                'required',
                Rule::in([TestScript::TYPE_RESPONSE, TestScript::TYPE_REQUEST]),
            ],
            'rules' => ['required'],
        ]);
        $this->assertTrue($testScript->save());
    }

    /**
     * Test TestScript delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testTestScriptDelete()
    {
        $testScript = factory(TestScript::class)->create();
        $testScript->delete();
        $this->assertDeleted($testScript);
    }
}
