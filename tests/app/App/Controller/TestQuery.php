<?php
/**
 * TestQuery
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller;


use Arvici\Component\Controller\BaseController;

class TestQuery extends BaseController
{

    public function basicQuery()
    {
        if ($this->request->get()->exists('test')) {
            throw new \Exception("Done", 999);
        }
    }
}