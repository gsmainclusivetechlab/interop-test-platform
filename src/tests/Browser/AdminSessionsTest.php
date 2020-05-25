<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use Tests\Browser\Pages\AdminSessionsPage;

class AdminSessionsTest extends DuskTestCase
{
    private $admin;
    private $user;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->user = factory(User::class)->create([
            'role' => User::ROLE_USER
        ]);
    }

    /**
     * Admin can see sessions created by users.
     * @return void
     */
    public function testAdminCanSeeSessionsCreatedByUsers()
    {
        $this->browse(function ($userBrowser, $adminBrowser) {
            $userBrowser
                ->loginAs($this->user)
                ->visit('sessions/register/sut')
                ->with('.fixed-bottom', function ($box) {
                    $box
                        ->press('Got It!');
                })
                ->type('base_url', 'http://base_url.com')
                ->click('form .btn[type="submit"]')
                ->waitForLocation('/sessions/register/info')
                ->type('form input[name="name"]', 'Test Session Creation')
                ->click('form .list-group > .list-group-item > .d-flex .btn')
                ->click('form .btn[type="submit"]')
                ->waitForLocation('/sessions/register/config')
                ->click('form .btn[type="submit"]');

            $adminBrowser
                ->loginAs($this->admin)
                ->visit(new AdminSessionsPage)
                ->assertSeeIn('@sessionsTable', 'Test Session Creation');
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
