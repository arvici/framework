<?php
use Arvici\Heart\Config\Configuration as Configure;


/**
 * Config: app
 */
Configure::define('app', function ($config) {
    return [
        /**
         * Base URL
         * Fill in the base url, in this location the public/ should be mapped.
         * Make sure you don't put a slash after the url!
         */
        'url' => 'http://localhost:8080',

        /**
         * Application Environment
         * This will affect several components of the framework, such as Logging and showing errors.
         *
         * You can set this to:
         *      - production      => No errors, exceptions are shown, all logged to log files.
         *      - development     => All errors, exceptions etc are thrown and showed in pages.
         */
        'env' => 'development',

        /**
         * Enable logging. Recommended to turn logging on!
         */
        'log' => true,

        /**
         * When env = development, show visual exception in browser.
         */
        'visualException' => true,

        /**
         * Logs directory.
         */
        'logPath' => BASEPATH . 'logs' . DS,

        /**
         * Define log files. Leave default to log all levels to one log file.
         * Syntax is: key = filename, value = minimum log level. Leave null for all levels.
         */
        'logFile' => [
            'error.log' => \Logger::ERROR,
            'development.log' => \Logger::DEBUG // Delete this one to improve performance when releasing in production!
        ],

        /**
         * Time Zone
         * The php timezone.
         * Could be 'UTC' too.
         *
         * @see http://php.net/manual/en/timezones.php
         */
        'timezone' => 'Europe/Amsterdam',

        /**
         * Default locale
         * default used by the translation engine or when not using it will only be used
         * in the template html tag, and debugging.
         */
        'locale' => 'en',

        /**
         * Private Secret Key
         * Will be used for several security related tasks.
         * Should be random!
         */
        'private_key' => 'J7a6dhaA&*dhgAfhjkHJv*78gja8gjKg89(*Sf',

        /**
         * Extra components to load from vendor.
         */
        'apps' => [
            '\App\App',
            '\SecondApp\SecondApp',
        ],

        /**
         * Session Configuration, set value to false to disable session.
         */
        'session' => [
            'name' => 'arvici_test_session', // Cookie name
            'expire' => 1*60*60, // 1 hour default
            'path' => '/', // / default
            'domain' => null, // null default
            'secure' => false, // secure flag (false default)
            'http' => true, // http only flag (true default)
            'prefix' => 'arvici_' // session key prefix (arvici_ default)
        ],

        /**
         * Path to the cache folder. Will be used for caching several framework components.
         */
        'cache' => BASEPATH . 'cache/',
    ];
});
