<?php
/**
 * Basic Router
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component;
use Arvici\Heart\Router\Middleware;
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
     * Group name
     * @var null|string
     */
    private static $group = null;

    /**
     * Get instance of the router.
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) { // @codeCoverageIgnore
            self::$instance = new self(); // @codeCoverageIgnore
        } // @codeCoverageIgnore
        return self::$instance;
    }

    /**
     * Will define the route entries in a closure.
     * First parameter of closure is an Router instance.
     *
     * @param \Closure $closure
     */
    public static function define(\Closure $closure)
    {
        call_user_func($closure, self::getInstance());
    }

    /**
     * Appending route entries to group.
     *
     * @param string $group
     * @param \Closure $closure
     */
    public static function group($group, \Closure $closure)
    {
        self::$group = $group;
        call_user_func($closure, self::getInstance());
        self::$group = null;
    }

    public static function api($schemaClass, $apiController, $methods = null)
    {
        // TODO: Generate route definitions for api class schema and methods.
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
        $this->addRoute(new Route($match, 'GET', $callback, self::$group));
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
        $this->addRoute(new Route($match, 'POST', $callback, self::$group));
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
        $this->addRoute(new Route($match, 'PUT', $callback, self::$group));
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
        $this->addRoute(new Route($match, 'DELETE', $callback, self::$group));
    }

    /**
     * Add before middleware.
     *
     * @param string|callable $callback
     * @param string $group Optional group. leave empty for global. When in group define it will be filled with the active group name!
     * @param array $methods Array of methods, leave undefined for all methods, 'GET'/'POST'/'PUT'/'DELETE'.
     */
    public function before($callback, $group = null, $methods = array())
    {
        $methods = array_map('strtoupper', $methods);
        if ($group === null) {
            if (self::$group !== null) {
                $group = self::$group;
            } else {
                $group = 'global';
            }
        }

        $this->addMiddleware(new Middleware($callback, 'before', $methods, $group));
    }

    /**
     * Add after middleware.
     *
     * @param string|callable $callback
     * @param string $group Optional group. leave empty for global. When in group define it will be filled with the active group name!
     * @param array $methods Array of methods, leave undefined for all methods, 'GET'/'POST'/'PUT'/'DELETE'.
     */
    public function after($callback, $group = null, $methods = array())
    {
        $methods = array_map('strtoupper', $methods);
        if ($group === null) {
            if (self::$group !== null) {
                $group = self::$group;
            } else {
                $group = 'global';
            }
        }

        $this->addMiddleware(new Middleware($callback, 'after', $methods, $group));
    }
}