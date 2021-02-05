<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use ClaudioDekker\Inertia;

class TutorialControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTutorialController()
    {
        $response = $this->get('/tutorials');
        $response->assertStatus(302);
    }
}
