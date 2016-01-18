<?php
/**
 * View Class
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\View;

/**
 * View (Files rendering group and manager)
 *
 * @package Arvici\Component\View
 */
class View
{
    /**
     * Start building a view.
     *
     * @return Builder
     */
    public static function build()
    {
        return new Builder();
    }
}