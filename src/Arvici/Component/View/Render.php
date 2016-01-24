<?php
/**
 * Render - View Stack, prepare for rendering.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\View;
use Arvici\Exception\RendererException;
use Arvici\Heart\Collections\DataCollection;

/**
 * Render holds view instances in order to render to the screen.
 *
 * @package Arvici\Component\View
 */
class Render
{
    /** @var Render */
    public static $instance = null;

    /**
     * Get render instance.
     *
     * @return Render
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /** @var DataCollection $stack Holds view instances. */
    private $stack;

    /** @var int $bodyKey Cache the body key here. */
    private $bodyKey;

    private function __construct()
    {
        $this->stack = new DataCollection();
    }

    /**
     * Add a view instance to the stack.
     *
     * @param View $view The view to add to the stack.
     * @return int number of views in the stack.
     */
    public function add(View $view)
    {
        // Append to the stack.
        $this->stack[] = $view;

        return count($this->stack);
    }

    /**
     * Remove view by key. (integer number).
     * @param int $key
     * @return bool Successfully removed?
     */
    public function remove($key)
    {
        if ($this->stack->exists($key)) {
            $this->stack->remove($key);
            return true;
        }
        return false;
    }

    /**
     * Set the body replacement view.
     *
     * @param View $view body view
     *
     * @throws RendererException
     */
    public function body(View $view)
    {
        if ($view->getType() !== View::PART_BODY) {
            throw new RendererException("You are replacing the body with a non-body view!");
        }

        if ($this->bodyKey === null) {
            throw new RendererException("No body is defined in the template!");
        }

        $this->stack[$this->bodyKey] = $view;
    }

    /**
     * Get raw collection (the stack itself).
     *
     * @return DataCollection
     */
    public function raw()
    {
        return $this->stack;
    }

    /**
     * Render all views in the stack.
     */
    public function run()
    {
        // TODO: Make the rendering start here.
    }
}