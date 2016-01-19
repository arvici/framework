<?php
/**
 * Arvici App Bootstrap
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

$configDir = __DIR__ . '/App/Config/';

require_once $configDir . 'App.php';
require_once $configDir . 'Router.php';
require_once $configDir . 'Template.php';


// Prepare any logging
// TODO: Implement logger here.

// Run the router.
\Arvici\Component\Router::getInstance()->run();
