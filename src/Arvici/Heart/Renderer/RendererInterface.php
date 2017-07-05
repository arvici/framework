<?php
/**
 * Interface for several renderers.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
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
     * @return string|void
     */
    public function render(View $template, array $data = array());

    /**
     * Get the default extension (php or twig for example).
     *
     * @return string
     */
    public static function getExtension();
}
