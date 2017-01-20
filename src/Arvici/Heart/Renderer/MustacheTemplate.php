<?php
/**
 * Mustache Template
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Exception\ConfigurationException;
use Arvici\Exception\FileNotFoundException;
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
            throw new ConfigurationException("The configuration for the APP.CACHE entry is invalid, should be a path to the cache folder!"); // @codeCoverageIgnore
        }

        // TODO: Make cache heart component, for global cache folder prepare and usage.
        if (! file_exists($cacheFolder)) { // @codeCoverageIgnore
            $makeDir = mkdir($cacheFolder); // @codeCoverageIgnore
            if (! $makeDir) { // @codeCoverageIgnore
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . "'! Please look at your permissions!"); // @codeCoverageIgnore
            } // @codeCoverageIgnore
        } // @codeCoverageIgnore

        if (! file_exists($cacheFolder . DS . 'template')) {
            $makeDir = mkdir($cacheFolder . DS . 'template'); // @codeCoverageIgnore
            if (! $makeDir) { // @codeCoverageIgnore
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . DS . 'template' . "'! Please look at your permissions!"); // @codeCoverageIgnore
            } // @codeCoverageIgnore
        }

        if (! file_exists($cacheFolder . DS . 'template' . DS . 'mustache')) {
            $makeDir = mkdir($cacheFolder . DS . 'template' . DS . 'mustache'); // @codeCoverageIgnore
            if (! $makeDir) { // @codeCoverageIgnore
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . DS . 'template' . DS . 'mustache' . "'! Please look at your permissions!"); // @codeCoverageIgnore
            } // @codeCoverageIgnore
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
     *
     * @return string|void
     *
     * @throws ConfigurationException
     * @throws FileNotFoundException
     * @throws RendererException
     */
    public function render(View $template, array $data = array())
    {
        if (! empty($this->data)) {
            $data = array_merge($this->data, $data);
        }

        // Load file into string
        $source = file_get_contents($template->getFullPath());

        if ($source === false) {
            throw new FileNotFoundException("View file '" . $template->getFullPath() . "' is not found!"); // @codeCoverageIgnore
        }

        return $this->mustache->render($source, $data);
    }
}
