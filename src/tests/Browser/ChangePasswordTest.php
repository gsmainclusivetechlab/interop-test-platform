<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ChangePasswordPage;

class ChangePasswordTest extends DuskTestCase
{
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
     * @test
     * Can navigate to profile settings page.
     * @return void
     */
    public function canNavigateToProfileSettingsPage()
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
     * @test
     * Can navigate to reset password page.
     * @return void
     */
    public function canNavigateToResetPasswordPage()
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
     * @test
     * Can change profile password.
     * @return void
     */
    public function canChangeProfilePassword()
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
