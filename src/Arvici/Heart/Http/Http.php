<?php
/**
 * HttpService - Service Interface for the Http Heart component
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Http;


class Http
{
    /** @var Request */
    private $request;
    /** @var Response */
    private $response;

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
     * Private HttpService constructor.
     */
    private function __construct()
    {
        $this->request = new Request();
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
}