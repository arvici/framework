<?php
/**
 * Route Entry
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Router;

use Arvici\Component\Controller\Controller;
use Arvici\Exception\ControllerNotFoundException;
use Arvici\Exception\RouterException;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Tools\DebugBarHelper;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response;


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
     * @var array
     */
    private $kwargs;

    /**
     * Route constructor.
     *
     * @param string $match Match url, can contain regexpressions.
     * @param array|string $methods Single or multiple method names.
     * @param callable $callback Callback to call when it's done.
     * @param null|string $group Group of route.
     * @param array $kwargs Any optional kwargs that will be passed to the controller (via route).
     */
    public function __construct($match, $methods, $callback, $group = null, $kwargs = [])
    {
        if (is_string($methods)) {
            $methods = array($methods);
        }
        $this->methods = $methods;
        $this->match = str_replace(array_keys(self::$patterns), array_values(self::$patterns), $match);
        $this->callback = $callback;
        $this->group = $group;
        $this->kwargs = $kwargs;
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
     * Get kwargs value by key, return default if not found.
     *
     * @param string $key Key string
     * @param mixed $default
     * @return mixed|null
     */
    public function getValue($key, $default = null)
    {
        if (is_array($this->kwargs) && isset($this->kwargs[$key])) {
            return $this->kwargs[$key];
        }
        return $default;
    }

    /**
     * Execute the route callback with given parameters.
     *
     * @param string $compiled
     * @throws ControllerNotFoundException
     * @throws RouterException
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

        if (is_string($this->callback)) {
            // Will call the controller here
            if (stristr($this->callback, '::')) {
                $parts = explode('::', $this->callback);
                $controllerClass = $parts[0];
                $controllerMethod = $parts[1];
            } else {
                $controllerClass = $this->callback;
                $controllerMethod = 'dispatch';
            }

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

            // Make sure we only give the first parameters the method allow us to give.
            $methodMeta = new \ReflectionMethod($controller, $controllerMethod);
            if (count($params) !== $methodMeta->getNumberOfParameters() && count($params) >= $methodMeta->getNumberOfRequiredParameters()) {
                if ($methodMeta->getNumberOfParameters() <= count($params)) {
                    $params = array_splice($params, 0, $methodMeta->getNumberOfParameters());
                }
            }

            // Call the method
            $callable = [$controller, $controllerMethod];
        } elseif (is_callable($this->callback)) {
            $callable = $this->callback;
        } else {
            throw new RouterException('Invalid route!');
        }

        // Execute route.
        $response = call_user_func_array($callable, $params);

        // Convert string to response.
        if (is_string($response)) {
            $response = new Response($response);
        }
        if (is_int($response) && $response >= 100 && $response <= 599) {
            $response = new Response('', $response);
        }

        // Parse response.
        if ($response instanceof Response) {
            // ok
        } elseif ($response instanceof ResponseInterface) {
            $httpFoundationFactory = new HttpFoundationFactory();

            $response = $httpFoundationFactory->createResponse($response);
        } else {
            throw new RouterException('Route should return a valid response!');
        }

        $response->prepare(Http::getInstance()->getRequest());

        if (Configuration::get('app.env') === 'development' && Configuration::get('app.profiler', false)) {
            DebugBarHelper::getInstance()->inject($response);
        }

        $response->send();
    }
}
