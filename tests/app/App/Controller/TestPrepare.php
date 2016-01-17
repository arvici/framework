<?php
/**
 * TestPrepare
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller;


use Arvici\Component\Controller\BaseController;

class TestPrepare extends BaseController
{
    public function prepare()
    {
        return false;
    }

    public function get()
    {
        throw new \Exception("Get is called!");
    }
}