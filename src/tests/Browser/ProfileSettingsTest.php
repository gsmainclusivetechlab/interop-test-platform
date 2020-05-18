<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\ProfileSettingsPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileSettingsTest extends DuskTestCase
{
    use DatabaseMigrations;

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
     * Can navigate to change password page.
     * @return void
     */
    public function testCanNavigateToChangePasswordPage()
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
     * Profile data matches user data.
     * @return void
     */
    public function testProfileDataMatchesUserData()
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
     * Can update profile settings.
     * @return void
     */
    public function testCanUpdateProfileSettings()
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
