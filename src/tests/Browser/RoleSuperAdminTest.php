<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Tests\Browser\Pages\AdminUsersPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleSuperAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $admin;
    private $user;
    private $testCaseCreator;
    private $superAdmin;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->user([
            'first_name' => 'GSMA',
            'last_name' => 'Admin',
            'role' => User::ROLE_ADMIN
        ]);

        $this->user = $this->user([
            'first_name' => 'GSMA',
            'last_name' => 'User',
            'role' => User::ROLE_USER
        ]);

        $this->superAdmin = $this->user([
            'first_name' => 'GSMA',
            'last_name' => 'Super Admin',
            'role' => User::ROLE_SUPERADMIN
        ]);
    }

    /**
     * Change user role functionality is enabled
     * @return void
     */
    public function testChangeUserRoleFunctionalityIsEnabled()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:first-child', function ($tr) {
                    $tr
                        ->assertButtonEnabled('td:nth-last-child(3) .dropdown-toggle');
                });
        });
    }

    /**
     * Can block and unblock users with role admin.
     * @return void
     */
    public function testCanBlockAndUnblockUsersWithRoleAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:first-child td:last-child', function ($td) {
                    $td
                        ->click('.dropdown-toggle')
                        ->waitFor('.dropdown-menu')
                        ->with('.dropdown-menu', function ($dropdown) {
                            $dropdown
                                ->clickLink('Block');
                        });
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal
                        ->press('Confirm');
                })
                ->waitForText('User blocked successfully')
                ->assertVisible('@notificationBox')
                ->click('@blockedUsersLink')
                ->waitForLocation('/admin/users/trash')
                ->with('.card .table tbody tr:first-child', function ($tr) {
                    $tr
                        ->assertSeeLink($this->admin->email)
                        ->with('td:last-child', function ($td) {
                            $td
                                ->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown
                                        ->clickLink('Unblock');
                                });
                        });
                })
                ->waitForText('User unblocked successfully')
                ->assertVisible('@notificationBox');
        });
    }

    /**
     * Can delete users with role admin.
     * @return void
     */
    public function testCanDeleteUsersWithRoleAdmin()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:first-child', function ($tr) {
                    $tr
                        ->assertSeeLink($this->admin->email)
                        ->with('td:last-child', function ($td) {
                            $td
                                ->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown
                                        ->clickLink('Delete');
                                });
                        });
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal
                        ->press('Confirm');
                })
                ->waitForText('User deleted successfully')
                ->assertVisible('@notificationBox');
        });
    }

    /**
     * Can block and unblock users with role user.
     * @return void
     */
    public function testCanBlockAndUnblockUsersWithRoleUser()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:nth-child(2) td:last-child', function ($td) {
                    $td
                        ->click('.dropdown-toggle')
                        ->waitFor('.dropdown-menu')
                        ->with('.dropdown-menu', function ($dropdown) {
                            $dropdown
                                ->clickLink('Block');
                        });
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal
                        ->press('Confirm');
                })
                ->waitForText('User blocked successfully')
                ->assertVisible('@notificationBox')
                ->click('@blockedUsersLink')
                ->waitForLocation('/admin/users/trash')
                ->with('.card .table tbody tr:first-child', function ($tr) {
                    $tr
                        ->assertSeeLink($this->user->email)
                        ->with('td:last-child', function ($td) {
                            $td
                                ->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown
                                        ->clickLink('Unblock');
                                });
                        });
                })
                ->waitForText('User unblocked successfully')
                ->assertVisible('@notificationBox');
        });
    }

    /**
     * Can delete users with role user.
     * @return void
     */
    public function testCanDeleteUsersWithRoleUser()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:nth-child(2)', function ($tr) {
                    $tr
                        ->assertSeeLink($this->user->email)
                        ->with('td:last-child', function ($td) {
                            $td
                                ->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown
                                        ->clickLink('Delete');
                                });
                        });
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal
                        ->press('Confirm');
                })
                ->waitForText('User deleted successfully')
                ->assertVisible('@notificationBox');
        });
    }
}
