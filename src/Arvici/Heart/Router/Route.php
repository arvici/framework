<?php
/**
 * Route Entry
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Router;
use Arvici\Component\Controller\Controller;
use Arvici\Exception\ControllerNotFoundException;

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
        '(!?)' => '([^/]+)',
        '(!int)' => '(-?[0-9]+)',
        '(!all)' => '(.*+)'
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
     * Compiled String
     * @var string|null
     */
    private $compiled = null;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var string|null
     */
    private $group;

    /**
     * Route constructor.
     *
     * @param string $match Match url, can contain regexpressions.
     * @param array|string $methods Single or multiple method names.
     * @param callable $callback Callback to call when it's done.
     * @param null|string $group Group of route.
     */
    public function __construct($match, $methods, $callback, $group = null)
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        $this->methods = $methods;
        $this->match = str_replace(array_keys(self::$patterns), array_values(self::$patterns), $match);
        $this->callback = $callback;
        $this->group = $group;
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


    /**
     * @return null|string
     */
    public function getGroup()
    {
        return $this->group;
    }


    /**
     * Execute the route callback with given parameters.
     *
     * @param string $compiled
     * @throws ControllerNotFoundException
     */
    public function execute($compiled)
    {
        // Get matched parameters
        $matches = null;
        preg_match_all($this->getCompiled(), $compiled, $matches);

        $params = array();
        if (count($matches) > 1) {
            // Has parameters, parse.
            $params = array_slice($matches, 1);
            $params = array_map(function($param) {
                return $param[0];
            }, $params);
        }

        if (is_string($this->callback) && stristr($this->callback, '::')) {
            // Will call the controller here
            $parts = explode('::', $this->callback);
            $controllerClass = $parts[0];
            $controllerMethod = $parts[1];

            if (! class_exists($controllerClass)) {
                throw new ControllerNotFoundException("The controller declared in your route is not found: '{$controllerClass}'");
            }

            $controller = new $controllerClass();

            if (! $controller instanceof Controller) {
                throw new ControllerNotFoundException("The controller doesn't extend the abstract framework controller: '{$controllerClass}'");
            }

            if (! method_exists($controller, $controllerMethod)) {
                throw new ControllerNotFoundException("The controller doesn't have the method you declared in the route!: '{$controllerClass}' method: {$controllerMethod}");
            }

            // When the prepare is false we will stop!
            if ($controller->prepare() === false) {
                return;
            }

            // Call the method
            call_user_func_array(array($controller, $controllerMethod), $params);
        }elseif (is_callable($this->callback)) {
            call_user_func_array($this->callback, $params);
        }
    }
}