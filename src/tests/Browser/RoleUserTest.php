<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleUserTest extends DuskTestCase
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
            'role' => User::ROLE_USER
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
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
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
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->assertMissing('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/test-cases')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }
}