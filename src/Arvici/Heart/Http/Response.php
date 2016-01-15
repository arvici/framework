<?php
/**
 * Response
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Http;

/**
 * Response Handler
 *
 * @package Arvici\Heart\Http
 *
 * @api
 */
class Response
{
    private $headers = array();
    private $code = 200;

    private $send = false;

    /**
     * Response Constructor
     * Don't call this directly, use the controller or service providers!
     */
    public function __construct()
    {
        // Clean response
        $this->send = false;
    }
}
