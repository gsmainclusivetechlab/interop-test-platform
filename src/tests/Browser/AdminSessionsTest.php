<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Session;
use Tests\Browser\Pages\AdminSessionsPage;

class AdminSessionsTest extends DuskTestCase
{
    private $admin;
    private $user;
    private $session;

    /**
     * Admin can see sessions created by users.
     * @return void
     */
    public function testAdminCanSeeSessionsCreatedByUsers()
    {
        $this->browse(function ($browser) {
            $this->admin = factory(User::class)->create([
                'role' => User::ROLE_ADMIN
            ]);

            $this->user = factory(User::class)->create([
                'role' => User::ROLE_USER
            ]);

            $this->session = factory(Session::class)->create([
                'owner_id' => $this->user->id
            ]);

            $browser
                ->loginAs($this->admin)
                ->visit(new AdminSessionsPage)
                ->assertSeeIn('@sessionsTable', $this->session->name);
        });
    }

    /**
     * Admin can delete sessions created by users.
     * @return void
     */
    public function testAdminCanDeleteSessionsCreatedByUsers()
    {
        $this->browse(function ($browser) {
            $browser
                ->loginAs($this->admin)
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
