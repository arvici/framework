<?php
/**
 * TestQuery
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Controller;


use Arvici\Component\Controller\Controller;

class TestQuery extends Controller
{

    public function basicQuery()
    {
        if ($this->request->get()->exists('test')) {
            throw new \Exception("Done", 999);
        }
    }
}
