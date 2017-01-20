<?php
/**
 * TestMiddleware
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Middleware;


use Arvici\Component\Middleware\BaseMiddleware;

class TestMiddleware extends BaseMiddleware
{
    public function testThrow()
    {
        throw new \Exception("TEST");
    }
}
