<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\LoginPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->user();
    }

    /**
     * Can navigate to forgot password page.
     * @return void
     */
    public function testCanNavigateToForgotPasswordPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->click('@forgotPasswordLink')
                ->waitForLocation('/password/reset')
                ->assertSee('Forgot password');
        });
    }

    /**
     * Can navigate to register page.
     * @return void
     */
    public function testCanNavigateToRegisterPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->click('@registerLink')
                ->waitForLocation('/register')
                ->assertSee('Create new account');
        });
    }

    /**
     * Can not login with empty credentials.
     * @return void
     */
    public function testCanNotLoginWithEmptyCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->clear('@email')
                ->clear('@password')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'The email field is required.')
                ->assertSeeIn('@password + .invalid-feedback', 'The password field is required.')
                ->assertGuest();
        });
    }

    /**
     * Can not login with invalid credentials.
     * @return void
     */
    public function testCanNotLoginWithInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->type('@email', self::$userEmailInvalid)
                ->type('@password', self::$userPasswordInvalid)
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'These credentials do not match our records.')
                ->assertGuest();
        });
    }

    /**
     * Can not login with valid email and invalid password.
     * @return void
     */
    public function testCanNotLoginWithValidEmailAndInvalidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->type('@email', $this->user->email)
                ->type('@password', self::$userPasswordInvalid)
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'These credentials do not match our records.')
                ->assertGuest();
        });
    }

    /**
     * Can not login with invalid email and valid password.
     * @return void
     */
    public function testCanNotLoginWithInvalidEmailAndValidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->type('@email', self::$userEmailInvalid)
                ->type('@password', self::$userPassword)
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'These credentials do not match our records.')
                ->assertGuest();
        });
    }

    /**
     * Can login with valid credentials.
     * @return void
     */
    public function testCanLoginWithValidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new LoginPage)
                ->type('@email', $this->user->email)
                ->type('@password', self::$userPassword)
                ->click('@submitButton')
                ->waitForLocation('/')
                ->assertAuthenticated();
        });
    }

    /**
     * Can logout.
     * @return void
     */
    public function testCanLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->click('@userActions')
                ->waitFor('@logoutLink')
                ->click('@logoutLink')
                ->waitForLocation('/login')
                ->assertGuest();
        });
    }
}
