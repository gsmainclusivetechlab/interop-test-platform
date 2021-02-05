<?php

namespace Tests\Unit\Models;

use App\Models\Session;
use Tests\TestCase;

class SessionTest extends TestCase
{
    /**
     * Test Session store.
     *
     * @return void
     */
    public function testSessionStore()
    {
        $session = factory(Session::class)->make();
        $this->assertValidationPasses($session->getAttributes(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
        ]);
        $this->assertTrue($session->save());
    }

    /**
     * Test Session delete.
     *
     * @return void
     * @throws \Exception
     */
    public function testSessionDelete()
    {
        $session = factory(Session::class)->create();
        $session->delete();
        $this->assertDeleted($session);
    }
}
