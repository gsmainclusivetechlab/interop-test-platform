<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\PasswordResetPage;

class PasswordResetTest extends DuskTestCase
{
    /**
     * Can navigate to login page.
     * @return void
     */
    public function testCanNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->click('@loginLink')
                ->waitForLocation('/login')
                ->assertSee('Login to your account');
        });
    }

    /**
     * Can not receive password reset link with empty email field.
     * @return void
     */
    public function testCanNotReceivePasswordResetLinkWithEmptyEmailField()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->clear('@email')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'The email field is required.');
        });
    }

    /**
     * Can not receive password reset link with invalid email.
     * @return void
     */
    public function testCanNotReceivePasswordResetLinkWithInvalidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->type('@email', 'invalid-email@dom.com')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'We can\'t find a user with that e-mail address.');
        });
    }

    /**
     * Can receive password reset link with valid email.
     * @return void
     */
    public function testCanReceivePasswordResetLinkWithValidEmail()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create();
            $browser
                ->visit(new PasswordResetPage)
                ->type('@email', $user->email)
                ->press('@submitButton')
                ->waitForLocation('/login')
                ->waitForText('We have e-mailed your password reset link!')
                ->assertVisible('@notificationBox');
        });
    }
}
