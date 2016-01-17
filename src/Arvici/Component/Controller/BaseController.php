<?php
/**
 * Base Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\Controller;

/**
 * Abstract Base Controller
 *
 * @package Arvici\Component\Controller
 */
abstract class BaseController
{

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