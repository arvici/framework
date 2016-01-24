<?php
/**
 * Php Template renderer.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
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
     * @return string|void
     */
    public function render(View $template, array $data = array())
    {
        if (! empty($data)) {
            $this->data = $data;
        }


        // TODO: Implement render() method.
    }
}