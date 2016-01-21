<?php
/**
 * Interface for several renderers.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Heart\Collections\DataCollection;

/**
 * Interface for Render Engines
 *
 * @package Arvici\Heart\Renderer
 */
interface RendererInterface
{
    /**
     * Set data array for usage in the renderer.
     *
     * @param array|DataCollection $data
     * @return $this
     */
    public function setData($data);

    /**
     * Render template.
     *
     * @param View                  $template   Template view instance.
     * @param array|DataCollection  $data       Data.
     * @param bool                  $return     Return result, false will output with echo.
     * @return string|void
     */
    public function render(View $template, array $data = array(), $return = false);
}