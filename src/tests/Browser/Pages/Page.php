<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@body' => 'body',
            '@notificationBox' => '.noty_layout',
            '@userActions' => '.navbar-nav .nav-item:last-child .nav-link',
            '@invalidFormField' => '.form-control.is-invalid',
            '@submitButton' => 'form .btn[type="submit"]',

            '@email' => 'form [name="email"]',
            '@firstName' => 'form [name="first_name"]',
            '@lastName' => 'form [name="last_name"]',
            '@company' => 'form [name="company"]',
            '@code' => 'form [name="code"]',
            '@terms' => 'form [name="terms"]',
            '@password' => 'form [name="password"]',
            '@currentPassword' => 'form [name="current_password"]',
            '@passwordConfirmation' => 'form [name="password_confirmation"]',

            '@loginLink' => 'a[href$="/login"]',
            '@registerLink' => 'a[href$="/register"]',
            '@logoutLink' => '.navbar-nav a[href$="/logout"]',
            '@forgotPasswordLink' => 'a[href$="/password/reset"]',
            '@profileSettingsLink' => '.navbar-nav a[href$="/settings/profile"]',
        ];
    }
}
