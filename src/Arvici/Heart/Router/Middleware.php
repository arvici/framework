<?php
/**
 * Middleware Entry
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Router;
use Arvici\Component\Middleware\BaseMiddleware;
use Arvici\Exception\RouterException;

/**
 * Middleware Entry.
 *
 * @package Arvici\Heart\Router
 */
class Middleware
{
    /**
     * array with methods, empty is all.
     *
     * @var array
     */
    private $methods = array();

    /**
     * Position, could be 'before' or 'after'
     * @var string
     */
    private $position;

    /**
     * callback to execute.
     * @var callable
     */
    private $callback;

    /**
     * group to apply to. empty to global.
     * @var string|null
     */
    private $group = 'global';


    /**
     * Middleware constructor.
     * @param callable $callback Callback to trigger.
     *
     * @param string $position 'before', 'after'. before is default.
     *
     * @param array $methods Array with methods, or empty array for all methods.
     *
     * @param string|null $group Group name to apply to, leave undefined for global.
     */
    public function __construct($callback, $position = 'before', $methods = array(), $group = 'global')
    {
        $this->callback = $callback;
        $this->methods = $methods;
        $this->group = $group;
        $this->position = $position;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return callable
     * @codeCoverageIgnore
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return null|string
     */
    public function getGroup()
    {
        return $this->group;
    }


    /**
     * Execute Callback in middleware, capture the output of it.
     *
     * @return bool can we continue?
     *
     * @throws RouterException
     */
    public function execute()
    {
        if (is_string($this->callback)) {
            // Will call the controller here
            $parts = explode('::', $this->callback);
            $className = $parts[0];
            $classMethod = $parts[1];

            if (! class_exists($className)) {
                throw new RouterException("The callback class declared in your middleware is not found: '{$className}'");
            }

            $class = new $className();

            if (! $class instanceof BaseMiddleware) {
                throw new RouterException("The class in your middleware route doesn't extend the BaseMiddleware: '{$className}'"); // @codeCoverageIgnore
            }

            if (! method_exists($class, $classMethod)) {
                throw new RouterException("The class in your middleware route doesn't have the method you provided: '{$className}' method: {$classMethod}");
            }

            // Call the method. Catch return
            $result = call_user_func(array($class, $classMethod));

        } elseif (is_callable($this->callback)) {
            $result = call_user_func($this->callback);

        } else {
            throw new RouterException("Middleware callback is not a class or callable!"); // @codeCoverageIgnore
        }

        // See if we got a boolean back.
        if ($result === false && $this->position === 'before') {
            // We should stop!
            return false;
        }
        return true;
    }
}
