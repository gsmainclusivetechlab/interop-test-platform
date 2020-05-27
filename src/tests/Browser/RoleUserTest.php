<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class RoleUserTest extends DuskTestCase
{
    /**
     * Has no access to admin users page.
     * @return void
     */
    public function testHasNoAccessToAdminUsersPage()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->assertMissing('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/users')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }

    /**
     * Has no access to admin sessions page.
     * @return void
     */
    public function testHasNoAccessToAdminSessionsPage()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->assertMissing('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/sessions')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }

    /**
     * Has no access to admin test cases page.
     * @return void
     */
    public function testHasNoAccessToAdminTestCasesPage()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->assertMissing('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/test-cases')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }
}
