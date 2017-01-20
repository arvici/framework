<?php
/**
 * Php Template renderer.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Exception\ConfigurationException;
use Arvici\Heart\Collections\DataCollection;

/**
 * Php Template renderer.
 *
 * @package Arvici\Heart\Renderer
 */
class PhpTemplate implements RendererInterface
{
    private $data = array();

    /**
     * Set data array for usage in the renderer.
     *
     * @param array|DataCollection $data
     * @return $this
     *
     * @codeCoverageIgnore
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
     * @return string
     *
     * @throws ConfigurationException
     */
    public function render(View $template, array $data = array())
    {
        if (! empty($this->data)) { // @codeCoverageIgnore
            $data = array_merge($this->data, $data); // @codeCoverageIgnore
        } // @codeCoverageIgnore

        // Path
        $path = $template->getFullPath();

        // Declare all data vars local if possible
        foreach ($data as $name => $value) {
            ${$name} = $value;
        }

        // Require and stop with OB.
        ob_start();

        require $path;

        return ob_get_clean();
    }
}
