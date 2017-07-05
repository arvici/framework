<?php
/**
 * Twig Template
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
use Arvici\Heart\App\AppManager;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Config\Configuration;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

/**
 * Twig Template
 * @package Arvici\Heart\Renderer
 */
class TwigTemplate implements RendererInterface
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var LoaderInterface
     */
    private $loader;

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

        if (! file_exists($cacheFolder . DS . 'template' . DS . 'twig')) {
            $makeDir = mkdir($cacheFolder . DS . 'template' . DS . 'twig'); // @codeCoverageIgnore
            if (! $makeDir) { // @codeCoverageIgnore
                throw new PermissionDeniedException("Could not make directory '" . $cacheFolder . DS . 'template' . DS . 'twig' . "'! Please look at your permissions!"); // @codeCoverageIgnore
            } // @codeCoverageIgnore
        }

        // Prepare the instances
        $this->loader = new FilesystemLoader();

        foreach (AppManager::getInstance()->getApps() as $app) {
            if (is_dir($app->getAppDirectory() . DS . 'Template')) {
                $this->loader->addPath($app->getAppDirectory() . DS . 'Template');
            }
            if (is_dir($app->getAppDirectory() . DS . 'View')) {
                $this->loader->addPath($app->getAppDirectory() . DS . 'View');
            }
        }

        $isDebug = Configuration::get('app.env') === 'development';
        $cache = null;
        if ((! $isDebug && Configuration::get('cache.enabled', false))
            || ($isDebug && Configuration::get('cache.forceEnabled', false))) {
            $cache = $cacheFolder . DS . 'template' . DS . 'twig';
        }

        $this->environment = new Environment($this->loader, [
            'cache' => $cache,
            'debug' => $isDebug,
        ]);

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
        return $this;
    }

    /**
     * Render template.
     *
     * @param View $template Template view instance.
     * @param array|DataCollection $data Data.
     *
     * @return string
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

        return $this->environment->render($template->getPath(), $data);
    }

    /**
     * Get the default extension (php or twig for example).
     *
     * @return string
     */
    public static function getExtension()
    {
        return 'twig';
    }
}
