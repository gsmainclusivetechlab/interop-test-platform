<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\TutorialController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Tests\TestCase;

class DarkModeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDarkModeController()
    {

        $response = $this->post('/dark-mode');
        $response->assertCookie('dark_mode', true);
        $response->assertStatus(302);
    }
}
