<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Tests\Browser\Pages\AdminUsersPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $admin;
    private $user;
    private $testCaseCreator;

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
    }

    /**
     * Can block and unblock users with role user.
     * @return void
     */
    public function testCanBlockAndUnblockUsersWithRoleUser()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->admin)
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
                ->loginAs($this->admin)
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