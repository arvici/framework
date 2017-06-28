<?php
/**
 * TestPrepare
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Controller;


use Arvici\Component\Controller\Controller;

class TestPrepare extends Controller
{
    public function prepare()
    {
        return false;
    }

    public function get(...$params)
    {
        throw new \Exception("Get is called!");
    }
}
