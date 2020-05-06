<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

class ProfileSettingsTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * Create user.
     */
    protected static function createUser()
    {
        return factory(User::class)->create([
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'company' => 'Company',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password')
        ]);
    }

    /**
     * @test
     * Can navigate to profile settings page.
     * @return void
     */
    public function canNavigateToProfileSettingsPage()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->click('.navbar-nav .nav-item:last-child .nav-link')
                ->clickLink('Settings')
                ->waitForLocation('/settings/profile')
                ->assertSee('Profile');
        });
    }

    /**
     * @test
     * Can navigate to change password page.
     * @return void
     */
    public function canNavigateToChangePasswordPage()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/settings/profile')
                ->clickLink('Change password')
                ->waitForLocation('/settings/password')
                ->assertSee('Change password');
        });
    }

    /**
     * @test
     * Can navigate to reset password page.
     * @return void
     */
    public function canNavigateToResetPasswordPage()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/settings/password')
                ->clickLink('I forgot my password')
                ->waitForLocation('/password/reset')
                ->assertSee('Forgot password');
        });
    }

    /**
     * @test
     * Profile data matches user data.
     * @return void
     */
    public function profileDataMatchesUserData()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/settings/profile')
                ->assertInputValue('first_name', $user->first_name)
                ->assertInputValue('last_name', $user->last_name)
                ->assertInputValue('company', $user->company);
        });
    }

    /**
     * @test
     * Can update profile settings.
     * @return void
     */
    public function canUpdateProfileSettings()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/settings/profile')
                ->append('first_name', ' updated')
                ->append('last_name', ' updated')
                ->append('company', ' updated')
                ->press('Update profile')
                ->waitForText('Profile updated successfully')
                ->assertInputValue('first_name', $user->first_name . ' updated')
                ->assertInputValue('last_name', $user->last_name . ' updated')
                ->assertInputValue('company', $user->company . ' updated');
        });
    }

    /**
     * @test
     * Can change profile password.
     * @return void
     */
    public function canChangeProfilePassword()
    {
        $user = self::createUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/settings/password')
                ->assertSee('Change password')
                ->type('current_password', 'password')
                ->type('password', 'passwordUpdated')
                ->type('password_confirmation', 'passwordUpdated')
                ->press('Update password')
                ->waitForText('Password updated successfully')
                ->press('Update password')
                ->waitFor('.form-control.is-invalid')
                ->assertSee('The password is incorrect.');
        });

    }
}
