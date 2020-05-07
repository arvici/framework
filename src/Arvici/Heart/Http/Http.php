<?php
/**
 * HttpService - Service Interface for the Http Heart component
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Http;

use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Router\Route;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Http
{
    /** @var Request */
    private $request;

    /** @var Context */
    private $context;

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
        $this->request = Request::createFromGlobals();
        $this->context = new Context();

        $sessionConfig = Configuration::get('app.session', []);
        $sessionKey = isset($sessionConfig['name']) ? $sessionConfig['name'] : 'arvici_session';

        if (! $this->request->hasSession()) {
            $this->request->setSession(new Session(null, new AttributeBag($sessionKey)));
        }
        if (! headers_sent()) {
            $this->getSession()->setName($sessionKey);
            $this->getSession()->save();
        }
    }

    /**
     * Get request instance
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get PSR request implementation.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     * @codeCoverageIgnore
     */
    public function getPsrRequest()
    {
        $psr7Factory = new DiactorosFactory();
        return $psr7Factory->createRequest($this->request);
    }

    /**
     * Get session instance
     *
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->request->getSession();
    }

    /**
     * Get the route that matched with the request.
     *
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the active route.
     *
     * @param Route $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * Get the actual request context.
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
