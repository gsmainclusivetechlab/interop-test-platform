<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ProfileSettingsPage;

class ProfileSettingsTest extends DuskTestCase
{
    /**
     * Can navigate to change password page.
     * @return void
     */
    public function testCanNavigateToChangePasswordPage()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ProfileSettingsPage)
                ->click('@changePasswordLink')
                ->waitForLocation('/settings/password')
                ->assertSee('Change password');
        });
    }

    /**
     * Profile data matches user data.
     * @return void
     */
    public function testProfileDataMatchesUserData()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ProfileSettingsPage)
                ->assertInputValue('@firstName', $user->first_name)
                ->assertInputValue('@lastName', $user->last_name)
                ->assertInputValue('@company', $user->company);
        });
    }

    /**
     * Can update profile settings.
     * @return void
     */
    public function testCanUpdateProfileSettings()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(new ProfileSettingsPage)
                ->type('@firstName', 'newFirstName')
                ->type('@lastName', 'newLastName')
                ->type('@company', 'newCompany')
                ->click('@submitButton')
                ->waitForText('Profile updated successfully')
                ->assertInputValue('@firstName', 'newFirstName')
                ->assertInputValue('@lastName', 'newLastName')
                ->assertInputValue('@company', 'newCompany');
        });
    }
}
