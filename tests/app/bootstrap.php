<?php
/**
 * Arvici App Bootstrap.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */
use Arvici\Heart\Config\Configuration;

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
 * Set default timezone
 */
date_default_timezone_set(Configuration::get('app.timezone', 'UTC'));

/**
 * Start the logger.
 */
\Logger::start();

/**
 * Start the router.
 */
\Arvici\Component\Router::getInstance()->run();
