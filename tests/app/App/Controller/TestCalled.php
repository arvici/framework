<?php
/**
 * TestCalled
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller;


use Arvici\Component\Controller\Controller;

class TestCalled extends Controller
{
    public function get()
    {
        throw new \Exception("Get is called!", 999);
    }

    public function getParameters1($params)
    {
        $param1 = $params[0];
        $param2 = $params[1];

        if ($param1 == 11 && $param2 == 54) {
            throw new \Exception("Get is called!", 999);
        }
    }

    public function getParameters2($params)
    {
        $param1 = $params[0];
        $param2 = $params[1];
        
        if ($param1 === 'test' && $param2 == 555) {
            throw new \Exception("Get is called!", 999);
        }
    }
}