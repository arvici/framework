<?php
/**
 * Basic Router
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component;
use Arvici\Heart\Router\Route;

/**
 * Router
 *
 * @package Arvici\Component
 */
class Router extends \Arvici\Heart\Router\Router
{
    /**
     * @var Router
     */
    private static $instance;

    /**
     * Get instance of the router.
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self(); // @codeCoverageIgnore
        }
        return self::$instance;
    }

    /**
     * Will define the route entries in a closure.
     * First parameter of closure is an Router instance.
     *
     * @param \Closure $closure
     *
     * @codeCoverageIgnore
     */
    public static function define(\Closure $closure)
    {
        call_user_func($closure, self::getInstance());
    }


    /**
     * Add route for GET method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function get($match, $callback)
    {
        $this->addRoute(new Route($match, 'GET', $callback));
    }

    /**
     * Add route for POST method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function post($match, $callback)
    {
        $this->addRoute(new Route($match, 'POST', $callback));
    }

    /**
     * Add route for PUT method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function put($match, $callback)
    {
        $this->addRoute(new Route($match, 'PUT', $callback));
    }

    /**
     * Add route for DELETE method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function delete($match, $callback)
    {
        $this->addRoute(new Route($match, 'DELETE', $callback));
    }
}