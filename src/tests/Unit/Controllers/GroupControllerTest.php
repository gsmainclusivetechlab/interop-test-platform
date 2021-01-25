<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\TutorialController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGroupController()
    {
        $response = $this->get('/groups');
        $response->assertStatus(302);
    }
}
