<?php
/**
 * Render - View Stack, prepare for rendering.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component\View;
use Arvici\Exception\RendererException;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Renderer\RendererInterface;

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
            self::$instance = new self(); // @codeCoverageIgnore
        }
        return self::$instance;
    }


    /** @var DataCollection<View>|array<View> $stack Holds view instances. */
    private $stack;

    /** @var int $bodyKey Cache the body key here. */
    private $bodyKey;

    /** @var array $globalData Global Data, will be merged into the current data of the views. */
    private $globalData;

    /**
     * Render constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        $this->stack = new DataCollection();
        $this->globalData = array();
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
        $this->stack->append($view);

        if ($view->getType() === View::PART_BODY_PLACEHOLDER) {
            $this->bodyKey = (count($this->stack) - 1);
        }

        return count($this->stack);
    }

    /**
     * Remove view by key. (integer number).
     * @param int $key
     * @return bool Successfully removed?
     *
     * @codeCoverageIgnore
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
     * Set global data, will be merged and added on all the views.
     *
     * @param array $data
     */
    public function setGlobalData($data)
    {
        $this->globalData = $data;
    }

    /**
     * Check if we have a body placeholder still empty.
     *
     * @return bool
     */
    public function hasBodyPlaceholderEmpty()
    {
        if ($this->bodyKey !== null) {
            if (isset($this->stack[$this->bodyKey])) {
                if ($this->stack[$this->bodyKey]->getType() === View::PART_BODY_PLACEHOLDER) {
                    return true;
                }
            }
        }
        return false;
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
     * Replace all stack items for the array given
     *
     * @param array|DataCollection $stack
     * @param bool $resetBodyKey Reset the body placeholder. Will reindex the new stack for it.
     */
    public function replaceAll($stack, $resetBodyKey = true)
    {
        if (! $stack instanceof DataCollection) {
            $stack = new DataCollection($stack);
        }

        $this->stack = $stack;

        if ($resetBodyKey) {
            $this->bodyKey = null;

            foreach ($stack as $key => $view) {
                if ($view->getType() === View::PART_BODY_PLACEHOLDER) {
                    $this->bodyKey = $key;
                }
            }
        }
    }

    /**
     * Render all views in the stack.
     *
     * @param bool $return Return the rendered output?
     *
     * @return mixed|void
     *
     * @throws RendererException
     */
    public function run($return = false)
    {
        $output = "";

        foreach ($this->stack as $view) { /** @var View $view */
            $data = $view->getData();

            if (! is_array($data)) {
                $data = array();
            }

            if ($view->getType() === View::PART_BODY_PLACEHOLDER) {
                throw new RendererException("The body placeholder isn't replaced!"); // @codeCoverageIgnore
            }

            if (! empty($this->globalData)) {
                $data = array_merge($data, $this->globalData);
            }

            $engineClass = $view->getEngine();
            /** @var RendererInterface $engine */
            $engine = $engineClass->newInstance();

            if (! $engine instanceof RendererInterface) { // @codeCoverageIgnore
                throw new RendererException("Engine is not instance of the RendererInterface."); // @codeCoverageIgnore
            } // @codeCoverageIgnore

            // Render it!
            $output .= $engine->render($view, $data);
        }

        // If we need to return. Then just return it.
        if ($return) {
            return $output;
        }

        // Instead, push it into the response object and send.
        Http::getInstance()->response()->body($output)->send(200); // @codeCoverageIgnore
        return; // @codeCoverageIgnore
    }
}
