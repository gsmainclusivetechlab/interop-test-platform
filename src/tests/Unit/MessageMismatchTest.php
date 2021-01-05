<?php

namespace Tests\Unit;

use App\Models\MessageMismatch;
use Tests\TestCase;

class MessageMismatchTest extends TestCase
{
    /**
     * Test Message Mismatch store.
     *
     * @return void
     */
    public function testMessageMismatchStore()
    {
        $messageMismatch = factory(MessageMismatch::class)->make();
        $this->assertValidationPasses($messageMismatch->getAttributes(), [
            'request' => ['required', 'string', 'max:255'],
            'exception' => ['string'],
        ]);
        $this->assertTrue($messageMismatch->save());
    }

    /**
     * Test Message Mismatch delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testMessageMismatchDelete()
    {
        $messageMismatch = factory(MessageMismatch::class)->create();
        $messageMismatch->delete();
        $this->assertDeleted($messageMismatch);
    }
}
