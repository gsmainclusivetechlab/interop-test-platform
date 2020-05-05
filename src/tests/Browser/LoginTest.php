<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Login extends DuskTestCase
{

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
