<?php
/**
 * Html Template renderer.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Heart\Collections\DataCollection;

/**
 * Html Template renderer.
 *
 * @package Arvici\Heart\Renderer
 *
 * @codeCoverageIgnore
 */
class HtmlTemplate implements RendererInterface
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
        return $this;
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

    /**
     * Get the default extension (php or twig for example).
     *
     * @return string
     */
    public static function getExtension()
    {
        return 'html';
    }
}
