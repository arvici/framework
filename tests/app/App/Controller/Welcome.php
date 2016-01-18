<?php
/**
 * Welcome Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller;

use Arvici\Component\Controller\BaseController;

class Welcome extends BaseController
{
    public function index()
    {
        $this->response->body("<h1>Welcome</h1>")->send();
    }
}