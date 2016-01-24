<?php
/**
 * Mustache Template
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Exception\ConfigurationException;
use Arvici\Exception\PermissionDeniedException;
use Arvici\Exception\RendererException;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Config\Configuration;

/**
 * Mustache Template
 * @package Arvici\Heart\Renderer
 */
class MustacheTemplate implements RendererInterface
{
    /**
     * @var \Mustache_Engine
     */
    private $mustache;

    /**
     * @var array
     */
    private $data;

    /**
     * MustacheTemplate constructor.
     */
    public function __construct()
    {
        $cacheFolder = Configuration::get('app.cache', null);

        // Prepare the cache folder.
        if ($cacheFolder === null || empty($cacheFolder)) {
            throw new ConfigurationException("The configuration for the APP.CACHE entry is invalid, should be a path to the cache folder!");
        }

        if (! file_exists($cacheFolder . DS . 'template')) {
            $makeDir = mkdir($cacheFolder . DS . 'template');

            if (! $makeDir) {
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . DS . 'template' . "'! Please look at your permissions!");
            }
        }

        if (! file_exists($cacheFolder . DS . 'template' . DS . 'mustache')) {
            $makeDir = mkdir($cacheFolder . DS . 'template' . DS . 'mustache');

            if (! $makeDir) {
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . DS . 'template' . DS . 'mustache' . "'! Please look at your permissions!");
            }
        }

        // Prepare the instance
        $this->mustache = new \Mustache_Engine(array(
            'cache' => Configuration::get('app.cache', null),
            'charset' => 'UTF-8' // We will force utf8 here, as it will be widely used.
        ));

        $this->data = array();
    }

    /**
     * Set data array for usage in the renderer.
     *
     * @param array|DataCollection $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Render template.
     *
     * @param View $template Template view instance.
     * @param array|DataCollection $data Data.
     * @param bool $return Return result, false will output with echo.
     *
     * @return string|void
     *
     * @throws RendererException
     */
    public function render(View $template, array $data = array(), $return = false)
    {
        if ($template->getType() === View::PART_BODY_PLACEHOLDER) {
            throw new RendererException("The body placeholder isn't replaced!");
        }


        // TODO: Implement render() method.
    }
}