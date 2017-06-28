<?php
/**
 * Base Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component\Controller;

use Arvici\Exception\NotImplementedException;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Http\Request;
use Arvici\Heart\Http\Response;
use Arvici\Heart\Router\Route;

/**
 * Abstract Base Controller
 *
 * @package Arvici\Component\Controller
 */
abstract class Controller
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var Route */
    protected $route;

    /** @var array|null */
    protected $allowedMethods = null;

    /**
     * Controller constructor, will prepare the request and response objects.
     */
    public function __construct()
    {
        $this->request = Http::getInstance()->request();
        $this->response = Http::getInstance()->response();
        $this->route = Http::getInstance()->route();
    }

    /**
     * Do something before the route method is called.
     * You can stop the route by returning false in the prepare.
     *
     * @return bool
     */
    public function prepare()
    {
        // Prepare, just before the method for the route is called!
        return true;
    }

    /**
     * The router calls the dispatch of the controller at any time the defined route passes it's matching process.
     *
     * @param array ...$params
     * @throws NotImplementedException
     * @throws \Exception
     *
     * @return Response Should return a Response object that will end up at the client.
     */
    public function dispatch(...$params)
    {
        // Verify if the method is 'allowed' in our own context.
        $allowedMethods = $this->getAllowedMethods();
        if ($allowedMethods !== null && ! in_array($this->request->method(), $allowedMethods)) {
            // Not allowed. Return to sender.
        }

        // Check if the controller has the method requested.
        $meta = new \ReflectionClass($this);
        $method = strtolower($this->request->method());
        if (! $meta->hasMethod($method)) {
            throw new NotImplementedException('The method ' . $method . ' is not defined', 500);
        }

        // Call the method.
        return $this->{$method}($params);
    }

    /**
     * Accept the GET request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function get(...$params)
    {
        throw new NotImplementedException('The method get is not implemented', 500);
    }

    /**
     * Accept the POST request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function post(...$params)
    {
        throw new NotImplementedException('The method post is not implemented', 500);
    }

    /**
     * Accept the PUT request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function put(...$params)
    {
        throw new NotImplementedException('The method put is not implemented', 500);
    }

    /**
     * Accept the PATCH request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function patch(...$params)
    {
        throw new NotImplementedException('The method patch is not implemented', 500);
    }

    /**
     * Accept the DELETE request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function delete(...$params)
    {
        throw new NotImplementedException('The method delete is not implemented', 500);
    }

    /**
     * Accept the HEAD request.
     *
     * @param array ...$params Parameters to receive. Can be multiple.
     *
     * @return Response Should return response object.
     *
     * @throws \Exception Can throw several exceptions.
     */
    public function head(...$params)
    {
        throw new NotImplementedException('The method head is not implemented', 500);
    }

    // =================================================================================================================

    /**
     * Override this to have a dynamic allowedMethods value, depending on request.
     *
     * @return array|null List of allowed methods or null to allow everything.
     */
    protected function getAllowedMethods()
    {
        return $this->allowedMethods;
    }
}
