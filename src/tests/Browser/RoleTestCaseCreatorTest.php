<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class RoleTestCaseCreatorTest extends DuskTestCase
{
    private $user;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->user([
            'role' => User::ROLE_TEST_CASE_CREATOR
        ]);
    }

    /**
     * Has no access to admin users page.
     * @return void
     */
    public function testHasNoAccessToAdminUsersPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->assertPresent('.navbar-nav .admin-settings-dropdown')
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
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->assertPresent('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/sessions')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }

    /**
     * Has access to admin test cases page.
     * @return void
     */
    public function testHasAccessToAdminTestCasesPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->assertPresent('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/test-cases')
                ->assertSeeIn('.page-title', 'Test Cases');
        });
    }
}
