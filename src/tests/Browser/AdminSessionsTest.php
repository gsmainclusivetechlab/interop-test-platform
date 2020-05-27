<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Session;
use Tests\Browser\Pages\AdminSessionsPage;

class AdminSessionsTest extends DuskTestCase
{
    /**
     * Admin can see sessions created by users.
     * @return void
     */
    public function testAdminCanSeeSessionsCreatedByUsers()
    {
        $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $session = factory(Session::class)->create();
        $this->browse(function ($browser) use ($admin, $session) {
            $browser
                ->loginAs($admin)
                ->visit(new AdminSessionsPage)
                ->assertSeeIn('@sessionsTable', $session->name);
        });
    }

    /**
     * Admin can delete sessions created by users.
     * @return void
     */
    public function testAdminCanDeleteSessionsCreatedByUsers()
    {
        $admin = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $session = factory(Session::class)->create();
        $this->browse(function ($browser) use ($admin) {
            $browser
                ->loginAs($admin)
                ->visit(new AdminSessionsPage)
                ->with('@sessionsTable', function ($table) {
                    $table
                        ->click('tbody tr:first-child td:last-child .dropdown-toggle')
                        ->waitFor('.dropdown-menu')
                        ->with('.dropdown-menu', function ($dropdown) {
                            $dropdown
                                ->clickLink('Delete');
                        });
                })
                ->whenAvailable('.modal', function ($modal) {
                    $modal
                        ->press('Confirm');
                })
                ->waitForText('Session deleted successfully')
                ->assertVisible('@notificationBox');
        });
    }
}
