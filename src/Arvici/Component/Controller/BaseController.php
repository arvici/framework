<?php
/**
 * Base Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\Controller;
use Arvici\Component\View\Builder;
use Arvici\Component\View\View;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Http\Request;
use Arvici\Heart\Http\Response;

/**
 * Abstract Base Controller
 *
 * @package Arvici\Component\Controller
 */
abstract class BaseController
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var Builder */
    protected $view;

    /**
     * Controller constructor, will prepare the request and response objects.
     */
    public function __construct()
    {
        $this->request = Http::getInstance()->request();
        $this->response = Http::getInstance()->response();

        $this->view = View::build()->defaultStack();
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
}