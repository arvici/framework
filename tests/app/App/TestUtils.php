<?php
/**
 * TestUtils
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App;


use Arvici\Heart\Http\Http;
use Arvici\Heart\Router\Router;

class TestUtils
{
    public static function clear()
    {
        self::clearRoutes();
        self::clearHttp();
    }

    public static function clearRoutes()
    {
        Router::getInstance()->clearRoutes();
    }

    public static function clearHttp()
    {
        Http::clearInstance();
    }

    public static function spoofUrl($url, $method, $get = array(), $post = array(), $cookies = array(), $server = array())
    {
        $_SERVER['SCRIPT_NAME'] = 'index.php';
        $_SERVER['REQUEST_URI'] = $url;
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);

        $_GET = $get;
        $_POST = $post;
        $_COOKIE = $cookies;
        $_SERVER = array_merge($_SERVER, $server);
    }
}