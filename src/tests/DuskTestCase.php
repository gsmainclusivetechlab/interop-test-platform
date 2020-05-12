<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The default password to use for new accounts.
     *
     * @var string
     */
    protected static $userPassword = 'password123';

    /**
     * The invalid password to use for tests.
     *
     * @var string
     */
    protected static $userPasswordInvalid = 'userPasswordInvalid';

    /**
     * The invalid email to use for tests.
     *
     * @var string
     */
    protected static $userEmailInvalid = 'userEmailInvalid@gmail.com';

    /**
     * The default code to use for registration.
     *
     * @var string
     */
    protected static $userRegistrationCode = 'ITPBETA2020';

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--window-size=1920, 1080'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Tear down the test and delete all cookies from the browser instance to address
     * instances where the test would be kicked over to the login page.
     */
    protected function tearDown(): void
    {
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }

        parent::tearDown();
    }

    /**
     * Return a user model to authenticate against and use in the tests.
     *
     * @param array $attributes
     * @return \App\Models\User
     */
    protected function user(array $attributes = []): User
    {
        return factory(User::class)->create(array_merge([
            'password' => Hash::make(static::$userPassword),
        ], $attributes));
    }
}
