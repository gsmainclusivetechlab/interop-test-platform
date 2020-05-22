<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ChangePasswordPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChangePasswordTest extends DuskTestCase
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
     * Can navigate to profile settings page.
     * @return void
     */
    public function testCanNavigateToProfileSettingsPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit(new ChangePasswordPage)
                ->click('@profileSettingsLink')
                ->waitForLocation('/settings/profile')
                ->assertSee('Profile');
        });
    }

    /**
     * Can navigate to reset password page.
     * @return void
     */
    public function testCanNavigateToResetPasswordPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit(new ChangePasswordPage)
                ->click('@forgotPasswordLink')
                ->waitForLocation('/password/reset')
                ->assertSee('Forgot password');
        });
    }

    /**
     * Can change profile password.
     * @return void
     */
    public function testCanChangeProfilePassword()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit(new ChangePasswordPage)
                ->type('@currentPassword', self::$userPassword)
                ->type('@password', 'passwordUpdated')
                ->type('@passwordConfirmation', 'passwordUpdated')
                ->press('@submitButton')
                ->waitForText('Password updated successfully')
                ->assertVisible('@notificationBox');
        });
    }
}
