<?php
// IF NOT RUNNING PHPUNIT, EXIT DIRECTLY!!
if (! defined('PHPUNIT_RUNNING')) { exit(); }

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// Require composer autoload!
require_once dirname(__DIR__) . DS . 'vendor' . DS .'autoload.php';


/**
 * Define the base directory containing the 'App' folder.
 */
defined('BASEPATH') || define('BASEPATH', __DIR__ . DS . 'app/');

/**
 * Define all the paths in the base.
 */
defined('APPPATH') || define('APPPATH', BASEPATH . 'App' . DS);

/**
 * Load all configuration files.
 */
$configDir = APPPATH . '/Config/';
foreach (glob($configDir . '*.php') as $fileName) {
    require_once $fileName;
}

/**
 * Start the application
 */
$bootstrapper = new \Arvici\Heart\Bootstrapper();
$bootstrapper->startTest();
