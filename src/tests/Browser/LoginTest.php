<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Login extends DuskTestCase
{
    /**
     * @test
     * Can navigate to forgot password page.
     * @return void
     */
    public function can_navigate_to_forgot_password_page()
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
    public function can_navigate_to_register_page()
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
    public function can_not_login_with_empty_credentials()
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
    public function can_not_login_with_invalid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'invalid@email.com')
                ->type('password', 'invalid_password')
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
    public function can_not_login_with_valid_email_and_invalid_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'superadmin@gsma.com')
                ->type('password', 'invalid_password')
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
    public function can_not_login_with_invalid_email_and_valid_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'invalid@email.com')
                ->type('password', 'qzRBHEzStdG8XWhy')
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
    public function can_login_with_valid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to your account')
                ->type('email', 'superadmin@gsma.com')
                ->type('password', 'qzRBHEzStdG8XWhy')
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
    public function can_logout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('.navbar-nav .nav-item:last-child .nav-link')
                ->clickLink('Logout')
                ->waitForLocation('/login')
                ->assertGuest();
        });
    }
}
