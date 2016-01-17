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
 */
abstract class Router
{
    /**
     * Route registry
     * @var Route[]
     */
    private $routes = array();

    private $compiled;

    /**
     * Run the router
     *
     * @param string $method
     * @param string $url
     */
    public function run($method = null, $url = null)
    {
        if ($method === null) $method = $_SERVER['REQUEST_METHOD'];
        if ($url === null) {
            $url = $_SERVER['REQUEST_URI'];
            // Strip query from url
            if (strstr($url, '?')) {
                $parts = explode('?', $url, 2);
                $url = $parts[0];
            }
        }

        // Compile
        $this->compiled = strtoupper($method) . '~' . $url;

        // Try to match
        foreach($this->routes as $route) {
            if ($route->match($this->compiled)) {
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
     * Clear all routes
     */
    public function clearRoutes()
    {
        $this->routes = array();
    }


    /**
     * Will try to match parameters and execute the callback
     *
     * @param Route $route
     */
    private function executeRoute(Route &$route)
    {
        $route->execute($this->compiled);
    }
}