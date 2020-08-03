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
        $superAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use (
            $superAdmin,
            $admin,
            $user
        ) {
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage())
                ->with('.card .table tbody tr:nth-child(2)', function ($tr) {
                    $tr->assertButtonEnabled(
                        'td:nth-last-child(3) .dropdown-toggle'
                    );
                })
                ->with('.card .table tbody tr:nth-child(3)', function ($tr) {
                    $tr->assertButtonEnabled(
                        'td:nth-last-child(3) .dropdown-toggle'
                    );
                });
        });
    }

    /**
     * Can block and unblock users with role admin.
     * @return void
     */
    public function testCanBlockAndUnblockUsersWithRoleAdmin()
    {
        $superAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $this->browse(function (Browser $browser) use ($superAdmin, $admin) {
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage())
                ->with(
                    '.card .table tbody tr:nth-child(2) td:last-child',
                    function ($td) {
                        $td->click('.dropdown-toggle')
                            ->waitFor('.dropdown-menu')
                            ->with('.dropdown-menu', function ($dropdown) {
                                $dropdown->clickLink('Block');
                            });
                    }
                )
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Confirm');
                })
                ->waitForText('User blocked successfully')
                ->assertVisible('@notificationBox')
                ->click('@blockedUsersLink')
                ->waitForLocation('/admin/users/trash')
                ->with('.card .table tbody tr:first-child', function ($tr) use (
                    $admin
                ) {
                    $tr->assertSeeLink($admin->email)->with(
                        'td:last-child',
                        function ($td) {
                            $td->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown->clickLink('Unblock');
                                });
                        }
                    );
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
        $superAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $this->browse(function (Browser $browser) use ($superAdmin, $admin) {
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage())
                ->with('.card .table tbody tr:nth-child(2)', function (
                    $tr
                ) use ($admin) {
                    $tr->assertSeeLink($admin->email)->with(
                        'td:last-child',
                        function ($td) {
                            $td->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown->clickLink('Delete');
                                });
                        }
                    );
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Confirm');
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
        $superAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use ($superAdmin, $user) {
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage())
                ->with(
                    '.card .table tbody tr:nth-child(2) td:last-child',
                    function ($td) {
                        $td->click('.dropdown-toggle')
                            ->waitFor('.dropdown-menu')
                            ->with('.dropdown-menu', function ($dropdown) {
                                $dropdown->clickLink('Block');
                            });
                    }
                )
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Confirm');
                })
                ->waitForText('User blocked successfully')
                ->assertVisible('@notificationBox')
                ->click('@blockedUsersLink')
                ->waitForLocation('/admin/users/trash')
                ->with('.card .table tbody tr:first-child', function ($tr) use (
                    $user
                ) {
                    $tr->assertSeeLink($user->email)->with(
                        'td:last-child',
                        function ($td) {
                            $td->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown->clickLink('Unblock');
                                });
                        }
                    );
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
        $superAdmin = factory(User::class)->create([
            'role' => User::ROLE_SUPERADMIN,
        ]);
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        $this->browse(function (Browser $browser) use ($superAdmin, $user) {
            $browser
                ->loginAs($superAdmin)
                ->visit(new AdminUsersPage())
                ->with('.card .table tbody tr:nth-child(2)', function (
                    $tr
                ) use ($user) {
                    $tr->assertSeeLink($user->email)->with(
                        'td:last-child',
                        function ($td) {
                            $td->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu')
                                ->with('.dropdown-menu', function ($dropdown) {
                                    $dropdown->clickLink('Delete');
                                });
                        }
                    );
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Confirm');
                })
                ->waitForText('User deleted successfully')
                ->assertVisible('@notificationBox');
        });
    }
}
