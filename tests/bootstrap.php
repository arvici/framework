<?php
// IF NOT RUNNING PHPUNIT, EXIT DIRECTLY!!
if (! defined('PHPUNIT_RUNNING')) { exit(); }

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// Require composer autoload!
require_once dirname(__DIR__) . DS . 'vendor' . DS .'autoload.php';


if (!isset($_SESSION))
{
    // If we are run from the command line interface then we do not care
    // about headers sent using the session_start.
    if (PHP_SAPI === 'cli')
    {
        $_SESSION = array();
    }
    elseif (!headers_sent())
    {
        if (!session_start())
        {
            throw new Exception(__METHOD__ . 'session_start failed.');
        }
    }
    else
    {
        throw new Exception(
            __METHOD__ . 'Session started after headers sent.');
    }
}

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
$configDir = APPPATH . DS . 'Config';
foreach (glob($configDir . DS . '*.php') as $fileName) {
    if (preg_match("/\/Config\/([A-Za-z]+\.php)$/", $fileName)) {
        require_once $fileName;
    }
}

/**
 * Start the application
 */
$bootstrapper = new \Arvici\Heart\Bootstrapper();
$bootstrapper->startTest();
