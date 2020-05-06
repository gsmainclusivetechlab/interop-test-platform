<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Create user.
     */
    protected static function createUser()
    {
        return factory(User::class)->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('password')
        ]);
    }

    /**
     * @test
     * Can navigate to forgot password page.
     * @return void
     */
    public function canNavigateToForgotPasswordPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink('Forgot password?')
                ->waitForLocation('/password/reset')
                ->assertSee('Forgot password');
        });
    }

    /**
     * @test
     * Can navigate to register page.
     * @return void
     */
    public function canNavigateToRegisterPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink('Register')
                ->waitForLocation('/register')
                ->assertSee('Create new account');
        });
    }

    /**
     * @test
     * Can not login with empty credentials.
     * @return void
     */
    public function canNotLoginWithEmptyCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->clear('email')
                ->clear('password')
                ->press('Login')
                ->waitFor('.form-control.is-invalid')
                ->assertGuest();
        });
    }

    /**
     * @test
     * Can not login with invalid credentials.
     * @return void
     */
    public function canNotLoginWithInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'invalidEmail@gmail.com')
                ->type('password', 'invalidPassword')
                ->press('Login')
                ->waitFor('.form-control.is-invalid')
                ->assertGuest();
        });
    }

    /**
     * @test
     * Can not login with valid email and invalid password.
     * @return void
     */
    public function canNotLoginWithValidEmailAndInvalidPassword()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', $user->email)
                ->type('password', 'invalidPassword')
                ->press('Login')
                ->waitFor('.form-control.is-invalid')
                ->assertGuest();
        });
    }

    /**
     * @test
     * Can not login with invalid email and valid password.
     * @return void
     */
    public function canNotLoginWithInvalidEmailAndValidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'invalidEmail@gmail.com')
                ->type('password', 'password')
                ->press('Login')
                ->waitFor('.form-control.is-invalid')
                ->assertGuest();
        });
    }

    /**
     * @test
     * Can login with valid credentials.
     * @return void
     */
    public function canLoginWithValidCredentials()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->waitForLocation('/')
                ->assertAuthenticated();
        });
    }

    /**
     * @test
     * Can logout.
     * @return void
     */
    public function canLogout()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->click('.navbar-nav .nav-item:last-child .nav-link')
                ->clickLink('Logout')
                ->waitForLocation('/login')
                ->assertGuest();
        });
    }
}
