<?php
/**
 * TestObject
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App\Controller\Api;


use Arvici\Component\Controller\RestApiController;

class TestObject extends RestApiController
{

    /**
     * Get API Schema object with identifier.
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function find($identifier)
    {
        if ($identifier == 1) {
            throw new \Exception("TEST-GET");
        }
    }

    /**
     * Create new Schema Object with posted vars.
     */
    public function create()
    {
        throw new \Exception("TEST-POST");
    }

    /**
     * Update current object with putted vars.
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function replace($identifier)
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

    /**
     * Get API Schemas objects.
     * HTTP GET.
     */
    public function findAll()
    {
        throw new \Exception("TEST-FINDALL");
    }

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param mixed $identifier
     * @throws \Exception
     */
    public function update($identifier)
    {
        if ($identifier == 1) {
            throw new \Exception("TEST-UPDATE");
        }
    }
}