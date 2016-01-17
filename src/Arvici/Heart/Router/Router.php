<?php
/**
 * Arvici Router
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Router;

/**
 * Router
 *
 * @package Arvici\Heart\Router
 *
 * @api
 */
class Router
{
    /**
     * Route registry
     * @var Route[]
     */
    private $routes = array();

    /** @var Router */
    private static $instance;

    /**
     * Get Router instance
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * Router constructor.
     * @internal Constructor should never be called outside of the router, use singleton!
     */
    private function __construct()
    {

    }

    /**
     * Run the router
     *
     * @param string $method
     * @param string $url
     */
    public function run($method = null, $url = null)
    {
        if ($method === null) $method = $_SERVER['REQUEST_METHOD'];
        if ($url === null) $url = $_SERVER['REQUEST_URI'];

        // Compile
        $compiled = strtoupper($method) . '~' . $url;

        // Try to match
        foreach($this->routes as $route) {
            if ($route->match($compiled)) {
                $this->executeRoute($route);
            }
        }
    }


    /**
     * Add route to registry
     *
     * It's better to use the ->get ->post etc methods, this will create the Route class for you!
     *
     * @param Route $route
     */
    public function addRoute(Route &$route)
    {
        $this->routes[] = $route;
    }


    /**
     * Will try to match parameters and execute the callback
     *
     * @param Route $route
     */
    private function executeRoute(Route &$route)
    {
        $route->execute();
    }
}