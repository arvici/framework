<?php
/**
 * Welcome Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace SecondApp\Controller;

use Arvici\Component\Controller\BasicController;


class Welcome extends BasicController
{
    public function get(...$params)
    {
        return $this->view->body('welcome')->render();
    }
}
