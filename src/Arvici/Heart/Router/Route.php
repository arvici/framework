<?php
/**
 * Route Entry
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Router;

/**
 * Route Entry
 *
 * @package Arvici\Heart\Router
 *
 * @api
 */
class Route
{
    /**
     * Match the http methods
     * @var array
     */
    private $methods = array();

    /**
     * Match url, can contain regexpressions.
     * @var string
     */
    private $match;

    /**
     * Hold the url path parameter keys. In order!
     * @var array
     */
    private $parameters = array();

    /**
     * Route constructor.
     * @param string $match Match url, can contain regexpressions.
     * @param array|string $methods Single or multiple method names.
     * @param array $parameters Parameter names in name.
     */
    public function __construct($match, $methods = array(), $parameters = array())
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        $this->methods = $methods;
        $this->match = $match;
        $this->parameters = $parameters;
    }

    /**
     * Get Compiled Reg Expression key.
     *
     * @return string
     */
    public function getCompiledKey()
    {
        return "";
    }
}