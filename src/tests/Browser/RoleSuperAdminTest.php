<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Tests\Browser\Pages\AdminUsersPage;

class RoleSuperAdminTest extends DuskTestCase
{
    /**
     * Change user role functionality is enabled
     * @return void
     */
    public function testChangeUserRoleFunctionalityIsEnabled()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create(['role' => User::ROLE_USER]);
            $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
            $superAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
            $browser
                ->loginAs($superAdmin)
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
            $user = factory(User::class)->create(['role' => User::ROLE_USER]);
            $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
            $superAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
            $browser
                ->loginAs($superAdmin)
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
                ->with('.card .table tbody tr:first-child', function ($tr) use ($admin) {
                    $tr
                        ->assertSeeLink($admin->email)
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
            $user = factory(User::class)->create(['role' => User::ROLE_USER]);
            $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
            $superAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:first-child', function ($tr) use ($admin) {
                    $tr
                        ->assertSeeLink($admin->email)
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
            $user = factory(User::class)->create(['role' => User::ROLE_USER]);
            $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
            $superAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
            $browser
                ->loginAs($superAdmin)
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
                ->with('.card .table tbody tr:first-child', function ($tr) use ($user) {
                    $tr
                        ->assertSeeLink($user->email)
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
            $user = factory(User::class)->create(['role' => User::ROLE_USER]);
            $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
            $superAdmin = factory(User::class)->create(['role' => User::ROLE_SUPERADMIN]);
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage)
                ->with('.card .table tbody tr:nth-child(2)', function ($tr) use ($user) {
                    $tr
                        ->assertSeeLink($user->email)
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
