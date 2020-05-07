<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\RegisterPage;

class RegisterTest extends DuskTestCase
{
    /**
     * @test
     * Can navigate to login page.
     * @return void
     */
    public function canNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new RegisterPage)
                ->click('@loginLink')
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
            $browser
                ->visit(new RegisterPage)
                ->assertSee('Create new account')
                ->clear('@firstName')
                ->clear('@lastName')
                ->clear('@email')
                ->clear('@company')
                ->clear('@password')
                ->clear('@passwordConfirmation')
                ->clear('@code')
                ->uncheck('@terms')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@firstName + .invalid-feedback', 'The first name field is required.')
                ->assertSeeIn('@lastName + .invalid-feedback', 'The last name field is required.')
                ->assertSeeIn('@email + .invalid-feedback', 'The email field is required.')
                ->assertSeeIn('@company + .invalid-feedback', 'The company field is required.')
                ->assertSeeIn('@password + .invalid-feedback', 'The password field is required.')
                ->assertSeeIn('@passwordConfirmation + .invalid-feedback', 'The password confirmation field is required.')
                ->assertSeeIn('@code + .invalid-feedback', 'The code field is required.')
                ->assertSee('The terms field is required.');
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
            $browser
                ->visit(new RegisterPage)
                ->type('@firstName', 'John')
                ->type('@lastName', 'Doe')
                ->type('@email', 'john.doe@gmail.com')
                ->type('@company', 'GSMA')
                ->type('@password', self::$userPassword)
                ->type('@passwordConfirmation', self::$userPassword)
                ->type('@code', self::$userRegistrationCode)
                ->check('@terms')
                ->click('@submitButton')
                ->waitForLocation('/email/verify')
                ->assertSee('Verify Your Email Address');
        });
    }

    /**
     * @test
     * Can not register with existing email.
     * @return void
     */
    public function canNotRegisterWithExistingEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new RegisterPage)
                ->type('@firstName', 'John')
                ->type('@lastName', 'Doe')
                ->type('@email', 'john.doe@gmail.com')
                ->type('@company', 'GSMA')
                ->type('@password', self::$userPassword)
                ->type('@passwordConfirmation', self::$userPassword)
                ->type('@code', self::$userRegistrationCode)
                ->check('@terms')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'The email has already been taken.');
        });
    }
}
