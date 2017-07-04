<?php
/**
 * Arvici App Bootstrap.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

/**
 * Define the base directory containing the 'App' folder.
 */
defined('BASEPATH') || define('BASEPATH', __DIR__ . DS);

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
$bootstrapper->startWeb();
