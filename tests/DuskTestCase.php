<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

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
        /*
         * If the OS is Linux, assume we're running in a non-graphical environment.
         * Linux desktop users, if you want to add a check to detect a graphical environment,
         * please go for it.
         */
        $args = [];

        if (PHP_OS === 'Linux') {
            $args = array_merge($args, [
                '--disable-gpu',
                '--headless',
                '--window-size=1920,1080',
            ]);
        }
        $options = (new ChromeOptions)->addArguments($args);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
