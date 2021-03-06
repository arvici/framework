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
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     * @param array|null $methods Methods to generate. Null for all. Example: ['GET'] or ['GET', 'POST'].
     *
     * @throws RouterException
     */
    public function api($match, $apiController, $kwargs = [], $methods = null)
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        if (! is_array($kwargs)) { // @codeCoverageIgnore
            $kwargs = []; // @codeCoverageIgnore
        }
        if ($methods === null) {
            $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        }

        $methods = array_map('strtoupper', $methods);

        if ($match === '') { // @codeCoverageIgnore
            throw new RouterException('Your API route must contain a first match field when registering!'); // @codeCoverageIgnore
        }
        if (substr($match, 0, 1) !== '/') {
            $match = '/' . $match;
        }

        foreach ($methods as $method) {
            switch ($method)
            {
                case 'GET':
                    $this->get($match, $apiController, array_merge($kwargs, ['api_method' => 'list']));
                    $this->get($match . '/(!?)', $apiController, array_merge($kwargs, ['api_method' => 'retrieve']));
                    break;
                case 'POST':
                    $this->post($match, $apiController, array_merge($kwargs, ['api_method' => 'create']));
                    break;
                case 'PUT':
                    $this->put($match . '/(!?)', $apiController, array_merge($kwargs, ['api_method' => 'update']));
                    break;
                case 'PATCH':
                    $this->patch($match . '/(!?)', $apiController, array_merge($kwargs, ['api_method' => 'partialUpdate']));
                    break;
                case 'DELETE':
                    $this->delete($match . '/(!?)', $apiController, array_merge($kwargs, ['api_method' => 'destroy']));
                    break;
            }
        }
    }

    /**
     * Add route for GET method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function get($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'GET', $callback, self::$group, $kwargs));
    }

    /**
     * Add route for OPTIONS method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function options($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'OPTIONS', $callback, self::$group, $kwargs));
    }

    /**
     * Add route for POST method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function post($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'POST', $callback, self::$group, $kwargs));
    }

    /**
     * Add route for PUT method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function put($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'PUT', $callback, self::$group, $kwargs));
    }

    /**
     * Add route for DELETE method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function delete($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'DELETE', $callback, self::$group, $kwargs));
    }

    /**
     * Add route for DELETE method.
     *
     * @param string $match
     * @param string|callable $callback
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     *
     * @codeCoverageIgnore
     */
    public function patch($match, $callback, $kwargs = [])
    {
        $this->addRoute(new Route($match, 'PATCH', $callback, self::$group, $kwargs));
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
