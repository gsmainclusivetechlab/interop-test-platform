<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Tests\Browser\Pages\AdminUsersPage;

class RoleSuperAdminTest extends DuskTestCase
{
    private $superAdmin;
    private $admin;
    private $user;

    /**
     * Change user role functionality is enabled
     * @return void
     */
    public function testChangeUserRoleFunctionalityIsEnabled()
    {
        $this->browse(function (Browser $browser) {
            $this->superAdmin = factory(User::class)->create([
                'role' => User::ROLE_SUPERADMIN,
                'email' => 'superadmin@gsma.com'
            ]);

            $this->admin = factory(User::class)->create([
                'role' => User::ROLE_ADMIN,
                'email' => 'admin@gsma.com'
            ]);

            $this->user = factory(User::class)->create([
                'role' => User::ROLE_USER,
                'email' => 'user@gsma.com'
            ]);

            $browser
                ->loginAs($this->superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:nth-child(2)', function ($tr) {
                    $tr
                        ->assertButtonEnabled('td:nth-last-child(3) .dropdown-toggle');
                })
                ->with('.card .table tbody tr:nth-child(3)', function ($tr) {
                    $tr
                        ->assertButtonEnabled('td:nth-last-child(3) .dropdown-toggle');
                });
        });
    }

    /**
     * @depends testChangeUserRoleFunctionalityIsEnabled
     * Can block and unblock users with role admin.
     * @return void
     */
    public function testCanBlockAndUnblockUsersWithRoleAdmin()
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
                        ->assertSeeLink('admin@gsma.com')
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
                ->with('.card .table tbody tr:nth-child(2)', function ($tr) {
                    $tr
                        ->assertSeeLink('admin@gsma.com')
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
     * @depends testCanDeleteUsersWithRoleAdmin
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
                        ->assertSeeLink('user@gsma.com')
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
     * @depends testCanDeleteUsersWithRoleAdmin
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
                        ->assertSeeLink('user@gsma.com')
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
