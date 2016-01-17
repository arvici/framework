<?php
/**
 * Use for PHP build in server, route file.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */
// From root:
// php -S localhost:80 -t tests/public/ tests/server.php

define('DS', DIRECTORY_SEPARATOR);
$public = dirname(__FILE__) . DS . 'public' . DS;
$url = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$url = urldecode( $url );
$request = $public . $url;

if (($url !== '/') && file_exists($request)) {
    return false;
}

require_once $public .'index.php';
