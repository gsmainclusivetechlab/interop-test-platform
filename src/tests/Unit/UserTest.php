<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User creating.
     *
     * @return void
     */
    public function testUserStore()
    {
        $user = factory(User::class)->create();
        $dbUser = User::first();
        $this->assertNotNull($dbUser);

        $emptyUser= new User;
        $this->assertFalse(Validator::make($emptyUser->attributesToArray(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->passes());

        $validationFailedUser = new User([
            'first_name' => Str::random(500),
            'last_name' => Str::random(500),
            'company' => Str::random(500),
            'role' => 'test',
            'email' => 'test',
            'email_verified_at' => 'test',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(100),
        ]);
        $this->assertFalse(Validator::make($validationFailedUser->attributesToArray(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->passes());
    }
}
