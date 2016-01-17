<?php
/**
 * Arvici Framework - Easy, fast and reliable framework
 *
 * @package    Arvici
 * @author     Tom Valk <tomvalk@lt-box.info>
 */
if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Register auto loader
 */
require __DIR__ . '/../../vendor/autoload.php';


/**
 * Start bootstrap of our app.
 */
require __DIR__ . '/../app/bootstrap.php';
