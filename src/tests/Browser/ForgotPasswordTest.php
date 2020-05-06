<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordTest extends DuskTestCase
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
     * Can navigate to login page.
     * @return void
     */
    public function canNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset')
                ->assertSee('Forgot password')
                ->clickLink('send me back')
                ->waitForLocation('/login')
                ->assertSee('Login to your account');
        });
    }

    /**
     * @test
     * Can not receive password reset link with empty email field.
     * @return void
     */
    public function canNotReceivePasswordResetLinkWithEmptyEmailField()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset')
                ->assertSee('Forgot password')
                ->clear('email')
                ->press('Send me password reset link')
                ->waitFor('.form-control.is-invalid')
                ->assertSee('The email field is required.');
        });
    }

    /**
     * @test
     * Can not receive password reset link with invalid email.
     * @return void
     */
    public function canNotReceivePasswordResetLinkWithInvalidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset')
                ->assertSee('Forgot password')
                ->type('email', 'invalidEmail@gmail.com')
                ->press('Send me password reset link')
                ->waitFor('.form-control.is-invalid')
                ->assertSee('We can\'t find a user with that e-mail address.');
        });
    }

    /**
     * @test
     * Can receive password reset link with valid email.
     * @return void
     */
    public function canReceivePasswordResetLinkWithValidEmail()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/password/reset')
                ->assertSee('Forgot password')
                ->type('email', $user->email)
                ->press('Send me password reset link')
                ->waitForLocation('/login')
                ->assertSee('Login to your account')
                ->waitForText('We have e-mailed your password reset link!')
                ->assertVisible('.noty_layout');
        });
    }
}
