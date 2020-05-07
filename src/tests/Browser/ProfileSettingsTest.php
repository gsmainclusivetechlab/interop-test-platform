<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ProfileSettingsPage;

class ProfileSettingsTest extends DuskTestCase
{
    private $user;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->user([
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'company' => 'Company',
        ]);
    }

    /**
     * @test
     * Can navigate to change password page.
     * @return void
     */
    public function canNavigateToChangePasswordPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit(new ProfileSettingsPage)
                ->click('@changePasswordLink')
                ->waitForLocation('/settings/password')
                ->assertSee('Change password');
        });
    }

    /**
     * @test
     * Profile data matches user data.
     * @return void
     */
    public function profileDataMatchesUserData()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit(new ProfileSettingsPage)
                ->assertInputValue('@firstName', $this->user->first_name)
                ->assertInputValue('@lastName', $this->user->last_name)
                ->assertInputValue('@company', $this->user->company);
        });
    }

    /**
     * @test
     * Can update profile settings.
     * @return void
     */
    public function canUpdateProfileSettings()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
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
