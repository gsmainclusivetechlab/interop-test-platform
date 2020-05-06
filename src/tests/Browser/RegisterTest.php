<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * Can navigate to login page.
     * @return void
     */
    public function canNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Create new account')
                ->clickLink('Login')
                ->waitForLocation('/login')
                ->assertSee('Login to your account');
        });
    }

    /**
     * @test
     * Can not register without filling required fields.
     * @return void
     */
    public function canNotRegisterWithoutFillingRequiredFields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Create new account')
                ->clear('first_name')
                ->clear('last_name')
                ->clear('email')
                ->clear('company')
                ->clear('password')
                ->clear('password_confirmation')
                ->clear('code')
                ->uncheck('terms')
                ->press('Register')
                ->waitFor('.form-control.is-invalid')
                ->assertPresent('.form-control.is-invalid');
        });
    }

    /**
     * @test
     * Can register with filling required fields.
     * @return void
     */
    public function canRegisterWithFillingRequiredFields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Create new account')
                ->type('first_name', 'John')
                ->type('last_name', 'Doe')
                ->type('email', 'john.doe@email.com')
                ->type('company', 'GSMA')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->type('code', 'ITPBETA2020')
                ->check('terms')
                ->press('Register')
                ->waitForLocation('/email/verify')
                ->assertSee('Verify Your Email Address');
        });
    }
}
