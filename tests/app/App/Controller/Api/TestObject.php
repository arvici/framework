<?php
/**
 * TestObject
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller\Api;


use Arvici\Component\Controller\ApiController;

class TestObject extends ApiController
{

    /**
     * Get API Schema object with identifier.
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function get($identifier)
    {
        if ($identifier == 1) {
            throw new \Exception("TEST-GET");
        }
    }

    /**
     * Create new Schema Object with posted vars.
     */
    public function post()
    {
        throw new \Exception("TEST-POST");
    }

    /**
     * Update current object with putted vars.
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function put($identifier)
    {
        if ($identifier == 1) {
            throw new \Exception("TEST-PUT");
        }
    }

    /**
     * Delete schema object with identifier.
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function delete($identifier)
    {
        if ($identifier == 1) {
            throw new \Exception("TEST-DELETE");
        }
    }
}