<?php
/**
 * View Class
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\View;
use Arvici\Heart\Collections\DataCollection;

/**
 * View (Files rendering group and manager)
 *
 * @package Arvici\Component\View
 */
class View
{
    /** This template part is for the template itself, could be the header or footer. Or any other static components of the stack. */
    const PART_TEMPLATE = 1;

    /** This is the most changing part, will be different on most of the controllers. Could be replaced if using default template stack. */
    const PART_BODY = 10;
    /** This will be the place were the body should be placed when using the template stack. */
    const PART_BODY_PLACEHOLDER = 11;

    /** Custom part. */
    const PART_CUSTOM = 90;

    /**
     * Start building a view.
     *
     * @return Builder
     */
    public static function build()
    {
        return new Builder();
    }


    /** @var string $path Path to the view. */
    private $path;
    /** @var int $type Type of the view. */
    private $type;
    /** @var DataCollection|array $data Data */
    private $data;
    /** @var \ReflectionClass Engine class. */
    private $engine;

    /**
     * Construct a view instance.
     *
     * @param string $path
     * @param int $type
     * @param string $engine The engine we will use, must be the class name of the engine.
     */
    public function __construct($path, $type = self::PART_BODY, $engine = 'PhpTemplate')
    {
        $this->path = $path;
        $this->type = $type;
        $this->engine = new \ReflectionClass("\\Arvici\\Heart\\Renderer\\" . $engine);
        $this->data = new DataCollection();
    }

    /**
     * Factory creator for template.
     *
     * @param string $path
     * @param string $engine
     *
     * @return View
     */
    public static function template($path, $engine = 'PhpTemplate')
    {
        return new self($path, self::PART_TEMPLATE, $engine);
    }

    /**
     * Factory creator for body.
     *
     * @param string $path
     * @param string $engine
     *
     * @return View
     */
    public static function body($path, $engine = 'PhpTemplate')
    {
        return new self($path, self::PART_BODY, $engine);
    }

    /**
     * Factory creator for body placeholder.
     *
     * @return View
     */
    public static function bodyPlaceholder()
    {
        return new self(null, self::PART_BODY_PLACEHOLDER);
    }


    /**
     * Set the data we will provide to the view.
     *
     * @param array|DataCollection $data Data to provide to the view.
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array|DataCollection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \ReflectionClass
     */
    public function getEngine()
    {
        return $this->engine;
    }
}