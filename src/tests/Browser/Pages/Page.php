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
            '@email' => 'form input[name="email"]',
            '@firstName' => 'form input[name="first_name"]',
            '@lastName' => 'form input[name="last_name"]',
            '@company' => 'form input[name="company"]',
            '@code' => 'form input[name="code"]',
            '@terms' => 'form input[name="terms"]',
            '@password' => 'form input[name="password"]',
            '@currentPassword' => 'form input[name="current_password"]',
            '@passwordConfirmation' => 'form input[name="password_confirmation"]',
            '@loginLink' => 'a[href$="/login"]',
            '@registerLink' => 'a[href$="/register"]',
            '@logoutLink' => '.navbar-nav a[href$="/logout"]',
            '@forgotPasswordLink' => 'a[href$="/password/reset"]',
            '@profileSettingsLink' => '.navbar-nav a[href$="/settings/profile"]',
        ];
    }
}
