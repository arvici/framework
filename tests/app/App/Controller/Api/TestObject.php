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
     * @param mixed $identifiers
     * @throws \Exception
     */
    public function find($identifiers)
    {
        if ($identifiers[0] == 1) {
            throw new \Exception("TEST-GET");
        }
    }

    /**
     * Create new Schema Object with posted vars.
     * @param array $identifiers
     * @throws \Exception
     */
    public function create($identifiers = array())
    {
        throw new \Exception("TEST-POST");
    }

    /**
     * Update current object with putted vars.
     *
     * @param mixed $identifiers
     * @throws \Exception
     */
    public function replace($identifiers)
    {
        if ($identifiers[0] == 1) {
            throw new \Exception("TEST-PUT");
        }
    }

    /**
     * Delete schema object with identifier.
     *
     * @param mixed $identifiers
     * @throws \Exception
     */
    public function delete($identifiers)
    {
        if ($identifiers[0] == 1) {
            throw new \Exception("TEST-DELETE");
        }
    }

    /**
     * Get API Schemas objects.
     * HTTP GET.
     * @param array $identifiers
     * @throws \Exception
     */
    public function findAll($identifiers = array())
    {
        throw new \Exception("TEST-FINDALL");
    }

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param mixed $identifiers
     * @throws \Exception
     */
    public function update($identifiers)
    {
        if ($identifiers[0] == 1) {
            throw new \Exception("TEST-UPDATE");
        }
    }
}