<?php
/**
 * Arvici Router
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Router;

use Arvici\Exception\AlreadyInitiatedException;
use Arvici\Exception\NotFoundException;
use Arvici\Heart\App\AppManager;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Tools\DebugBarHelper;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @var Route
     */
    private $notFoundRoute;

    private $compiled;

    /**
     * Middleware registry
     * @var Middleware[]
     */
    private $middleware = array();

    /**
     * Run the router
     *
     * @param string $method
     * @param string $url
     * @throws NotFoundException
     */
    public function run($method = null, $url = null)
    {
        // Initiate the apps.
        try {
            AppManager::getInstance()->initApps();
        } catch (AlreadyInitiatedException $exception) {
            // Ignore, this can happen when in tests for example.
        }

        // Prepare the session loading.
        if (! headers_sent()) { // @codeCoverageIgnore
            if (Http::getInstance()->getSession() !== null && ! Http::getInstance()->getSession()->isStarted()) { // @codeCoverageIgnore
                Http::getInstance()->getSession()->start(); // @codeCoverageIgnore
            }
        }

        // Begin parsing request.
        if ($method === null) $method = Http::getInstance()->getRequest()->getMethod();
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
                Http::getInstance()->setRoute($route);
                $this->executeRoute($route, $method);
                return;
            }
        }

        // Try to match debug routes.
        if (Configuration::get('app.env') === 'development' && Configuration::get('app.profiler', false)) {
            if (strpos(Http::getInstance()->getRequest()->getPathInfo(), '/__debug__') === 0) { // @codeCoverageIgnore
                $response = DebugBarHelper::getInstance()->processDebugRequest(Http::getInstance()->getRequest()); // @codeCoverageIgnore
                if ($response instanceof Response) { // @codeCoverageIgnore
                    $response->prepare(Http::getInstance()->getRequest()); // @codeCoverageIgnore
                    $response->send(); // @codeCoverageIgnore
                    return; // @codeCoverageIgnore
                }
            }
        }

        // 404
        if ($this->notFoundRoute === null) {
            throw new NotFoundException('Route not found!');
        }
        Http::getInstance()->setRoute($this->notFoundRoute);
        $this->executeRoute($this->notFoundRoute, $method);
    }


    /**
     * Add route to registry.
     *
     * It's better to use the ->get ->post etc methods, this will create the Route class for you!
     *
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * Add middleware trigger to registry.
     *
     * @param Middleware $middleware
     */
    public function addMiddleware(Middleware $middleware)
    {
        $group = $middleware->getGroup();
        if (! isset($this->middleware[$group])) {
            $this->middleware[$group] = array(
                'before' => array(),
                'after' => array()
            );
        }

        $this->middleware[$group][$middleware->getPosition()][] = &$middleware;
    }

    /**
     * Set the not found route.
     *
     * @param Route $route
     * @return $this
     */
    public function setNotFoundRoute(Route $route)
    {
        $this->notFoundRoute = $route;
        return $this;
    }

    /**
     * @return Route
     */
    public function getNotFoundRoute()
    {
        return $this->notFoundRoute;
    }

    /**
     * Clear all routes and middleware.
     */
    public function clearRoutes()
    {
        $this->routes =        [];
        $this->middleware =    [];
        $this->notFoundRoute = null;
    }

    /**
     * Will try to match parameters and execute the callback.
     * Will also trigger middleware.
     *
     * @param Route $route
     *
     * @param string $method Method of request.
     *
     * @param bool $force true for skipping middleware. Could be bad to do!
     *
     */
    private function executeRoute(Route &$route, $method, $force = false)
    {
        $continue = true;
        if (! $force) {
            $continue = $this->executeRouteMiddleware($route, 'before', $method);
        }

        if ($continue === true) {
            $route->execute($this->compiled);
        }

        if (! $force) {
            $this->executeRouteMiddleware($route, 'after', $method);
        }
    }

    /**
     * Execute middleware found for the route.
     *
     * @param Route $route
     *
     * @param string $position 'before' or 'after'
     *
     * @param string $method
     *
     * @return bool Continue?
     */
    private function executeRouteMiddleware(Route &$route, $position, $method)
    {
        $method = strtoupper($method);

        /** @var Middleware[] $middleware */
        $middleware = array();
        $continue   = true;

        // Get all global middleware's.
        if (isset(
                $this->middleware['global'],
                $this->middleware['global'][$position])
            && count($this->middleware['global'][$position]) > 0) {

            $middleware = array_merge($middleware, $this->middleware['global'][$position]);
        }

        // Group middleware if defined.
        if ($route->getGroup() !== null
            &&  isset($this->middleware[$route->getGroup()],
                $this->middleware[$route->getGroup()][$position])
            && count($this->middleware[$route->getGroup()][$position]) > 0) {
            $middleware = array_merge($middleware, $this->middleware[$route->getGroup()][$position]);
        }


        // Will execute all middleware in order..:
        foreach ($middleware as $trigger)
        {
            if (count($trigger->getMethods()) === 0
            ||  in_array(strtoupper($method), $trigger->getMethods())) {

                if ($this->executeMiddleware($trigger) === false) {
                    $continue = false;
                }
            }
        }

        return $continue;
    }

    /**
     * Execute middleware.
     *
     * @param Middleware $middleware
     *
     * @return bool
     *
     * @throws \Arvici\Exception\RouterException
     */
    private function executeMiddleware(Middleware &$middleware)
    {
        $continue = $middleware->execute();

        if ($continue === false) {
            return false;
        }
        return true;
    }
}
