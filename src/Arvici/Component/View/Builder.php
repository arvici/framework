<?php
/**
 * View Builder
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\View;
use Arvici\Exception\RendererException;
use Arvici\Heart\Config\Configuration;

/**
 * View Builder
 * @package Arvici\Component\View
 */
class Builder
{
    /** @var Render  */
    private $render;

    /** @var array $globalData Global data. */
    private $globalData = array();


    public function __construct()
    {
        $this->render = Render::getInstance();
    }

    /**
     * Load in the default stack
     *
     * @return Builder
     *
     * @throws RendererException
     */
    public function defaultStack()
    {
        $stack = Configuration::get('template.defaultStack');

        if (! is_array($stack) || empty($stack)) {
            throw new RendererException("The default stack, defined in the configuration is not defined or empty! Can't use it!");
        }

        $this->render->replaceAll($stack);

        return $this;
    }

    /**
     * Load a prepared stack.
     *
     * @param string $name
     *
     * @return Builder
     *
     * @throws RendererException
     */
    public function loadStack($name)
    {
        $stacks = Configuration::get('template.stacks');

        if (! isset($stacks[$name])) {
            throw new RendererException("Stack '" . $name . "' not found!");
        }

        $this->render->replaceAll($stacks[$name]);

        return $this;
    }


    /**
     * Add a view to the building stack.
     *
     * @param View $view
     *
     * @return Builder
     */
    public function addView(View $view)
    {
        $this->render->add($view);

        return $this;
    }

    /**
     * Set global available data array.
     *
     * @param array $data
     *
     * @return Builder
     *
     * @throws RendererException
     */
    public function data($data)
    {
        if (! is_array($data)) {
            throw new RendererException("Data provided should be an array!");
        }

        $this->globalData = $data;

        return $this;
    }

    /**
     * Add the body template view. When there is a placeholder still not filled in we will fill it with this body.
     *
     * @param string $path Path to the body view. (relative to the configuration view path).
     * @param array $data Data to pass to the view
     * @param string|null $engine Engine to use, don't fill it in to use the configurations one.
     *
     * @return Builder
     */
    public function body($path, $data = array(), $engine = null)
    {
        $view = new View($path, View::PART_BODY, $engine);
        $view->setData($data);

        if ($this->render->hasBodyPlaceholderEmpty()) {
            $this->render->body($view);
        } else {
            $this->render->add($view);
        }

        return $this;
    }

    /**
     * Add template part to the stack.
     *
     * @param string $path Path to the template, relative to the path you provided in the configuration.
     * @param array $data Data to pass.
     * @param string|null $engine Engine, leave null to use default one in config.
     *
     * @return Builder
     */
    public function template($path, $data = array(), $engine = null)
    {
        $view = new View($path, View::PART_TEMPLATE, $engine);
        $view->setData($data);

        $this->render->add($view);

        return $this;
    }

    /**
     *
     */
    public function render($data = array())
    {
        $this->render->setGlobalData(array_merge($this->globalData, $data));
        $this->render->run();
    }
}