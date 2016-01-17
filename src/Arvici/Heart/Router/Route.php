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
    private static $patterns = array(
        '(?)' => '([^/]+)',
        '(int)' => '-?[0-9]+',
        '(all)' => '.*'
    );

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
     * Compiled String
     * @var string|null
     */
    private $compiled = null;

    /**
     * @var callable
     */
    private $callback;

    /**
     * Route constructor.
     * @param string $match Match url, can contain regexpressions.
     * @param array|string $methods Single or multiple method names.
     * @param callable $callback Callback to call when it's done
     */
    public function __construct($match, $methods, $callback)
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        $this->methods = $methods;
        $this->match = str_replace(array_keys(self::$patterns), array_values(self::$patterns), $match);
        $this->callback = $callback;
    }

    /**
     * Get Compiled Reg Expression key.
     *
     * @return string
     */
    public function getCompiled()
    {
        if ($this->compiled === null) {
            $regexp = "/^";

            // Method(s)
            $methodIdx = 0;
            foreach($this->methods as $method) {
                $regexp .= "(?:" . strtoupper($method) . ")";
                if (($methodIdx + 1) < count($this->methods)) {
                    $regexp .= "|";
                }
                $methodIdx++;
            }

            // Separator
            $regexp .= "~";

            // Url
            $regexp .= str_replace('/', '\/', $this->match);

            $regexp .= "$/";

            $this->compiled = $regexp;
        }
        return $this->compiled;
    }

    /**
     * Try to match with the given compiled url.
     *
     * @param string $compiled
     * @return bool match
     */
    public function match($compiled)
    {
        return (bool) preg_match($this->getCompiled(), $compiled);
    }

    public function execute()
    {
        call_user_func_array($this->callback, array());
    }
}