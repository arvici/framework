<?php
/**
 * Welcome Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller;

use Arvici\Component\Controller\BaseController;
use Arvici\Exception\ControllerNotFoundException;

class Welcome extends BaseController
{
    public function index()
    {
        $this->view->body('welcome')->render();
    }

    public function json()
    {
        $this->response->json(array(
            'first' => true,
            'second' => true,
            'multi' => array(
                'Hello!'
            )
        ))->send();
    }

    public function exception()
    {
        throw new ControllerNotFoundException("Controller provided should give an exception on this route call!");
    }
}