<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class ProfileSettingsPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/settings/profile';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@changePasswordLink' => 'a[href$="/settings/password"]'
        ];
    }
}
