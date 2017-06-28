<?php
/**
 * HttpService - Service Interface for the Http Heart component
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Http;


use Arvici\Heart\Router\Route;

class Http
{
    /** @var Request */
    private $request;
    /** @var Response */
    private $response;
    /** @var Session */
    private $session;
    /** @var Route */
    private $route;

    /** @var Http */
    private static $instance;

    /**
     * Get HttpService Instance.
     *
     * @return Http
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Http();
        }
        return self::$instance;
    }

    /**
     * Clear instance, create new request and response
     */
    public static function clearInstance()
    {
        self::$instance = null;
    }

    /**
     * Private HttpService constructor.
     */
    private function __construct()
    {
        $this->session = new Session();
        $this->request = Request::detect($this->session);
        $this->response = new Response();
    }

    /**
     * Get response instance
     *
     * @return Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get request instance
     *
     * @return Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Get session instance
     *
     * @return Session
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * Get (or set) the route that matched with the request.
     *
     * @param Route|null $route
     *
     * @return Route
     */
    public function route($route = null)
    {
        if ($route !== null && $route instanceof Route) {
            $this->route = $route;
        }

        return $this->route;
    }
}
