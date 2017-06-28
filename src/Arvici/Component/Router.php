<?php
/**
 * Basic Router
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component;
use Arvici\Exception\RouterException;
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

    /**
     * Register an API entity class with a base match.
     *
     * @param string $match The base match for the list and create routes. The other routes will contain this as prefix.
     * @param string $apiController Api Controller class name, with full namespace notation.
     * @param array|null $methods Methods to generate. Null for all. Example: ['GET'] or ['GET', 'POST'].
     *
     * @throws RouterException
     */
    public function api($match, $apiController, $methods = null)
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        if ($methods === null) {
            $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        }

        $methods = array_map('strtoupper', $methods);

        if ($match === '') {
            throw new RouterException('Your API route must contain a first match field when registering!');
        }
        if (substr($match, -1, 1) !== '/') {
            $match .= '/';
        }
        if (substr($match, 0, 1) !== '/') {
            $match = '/' . $match;
        }

        foreach ($methods as $method) {
            switch ($method)
            {
                case 'GET':
                    $this->get($match, $apiController . '::dispatch');
                    $this->get($match . '/(!?)', $apiController . '::dispatch');
                    break;
                case 'POST':
                    $this->post($match, $apiController . '::dispatch');
                    break;
                case 'PUT':
                    $this->put($match . '/(!?)', $apiController . '::dispatch');
                    break;
                case 'PATCH':
                    $this->patch($match . '/(!?)', $apiController . '::dispatch');
                    break;
                case 'DELETE':
                    $this->delete($match . '/(!?)', $apiController . '::dispatch');
                    break;
            }
        }
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
     * Add route for OPTIONS method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function options($match, $callback)
    {
        $this->addRoute(new Route($match, 'OPTIONS', $callback, self::$group));
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
     * Add route for DELETE method.
     *
     * @param string $match
     * @param string|callable $callback
     *
     * @codeCoverageIgnore
     */
    public function patch($match, $callback)
    {
        $this->addRoute(new Route($match, 'PATCH', $callback, self::$group));
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
