<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class UserRoleTest extends DuskTestCase
{
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
     * Has no access to admin pages.
     * @return void
     */
    public function testHasNoAccessToAdminPages()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user)
                ->visit('/')
                ->assertMissing('.navbar-nav .admin-settings-dropdown')
                ->visit('/admin/users')
                ->assertSee('We are sorry but you do not have permission to access this page')
                ->visit('/admin/sessions')
                ->assertSee('We are sorry but you do not have permission to access this page');
        });
    }
}
