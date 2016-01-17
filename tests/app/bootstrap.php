<?php
/**
 * Arvici App Bootstrap
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

/** @noinspection PhpIncludeInspection */
require_once __DIR__ . DS . 'App' . DS . 'Config' . DS . 'App.php';

/** @noinspection PhpIncludeInspection */
require_once __DIR__ . DS . 'App' . DS . 'Config' . DS . 'Router.php';


// Run the router.
\Arvici\Component\Router::getInstance()->run();
