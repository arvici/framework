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

    private $engine;

    /**
     * Construct a view instance.
     *
     * @param string $path
     * @param int $type
     * @param string $engine The engine we will use, must be a constants value of the rendering engine.
     */
    public function __construct($path, $type, $engine)
    {
        $this->path = $path;
        $this->type = $type;
        $this->data = new DataCollection();
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
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }
}