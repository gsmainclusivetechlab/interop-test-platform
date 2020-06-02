<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ChangePasswordPage;

class ChangePasswordTest extends DuskTestCase
{
    /**
     * Can navigate to profile settings page.
     * @return void
     */
    public function testCanNavigateToProfileSettingsPage()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ChangePasswordPage())
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
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ChangePasswordPage())
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
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ChangePasswordPage())
                ->type('@currentPassword', 'password')
                ->type('@password', 'passwordUpdated')
                ->type('@passwordConfirmation', 'passwordUpdated')
                ->press('@submitButton')
                ->waitForText('Password updated successfully')
                ->assertVisible('@notificationBox');
        });
    }
}
