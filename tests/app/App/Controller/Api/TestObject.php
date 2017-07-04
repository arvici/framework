<?php
/**
 * TestObject
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Controller\Api;


use Arvici\Component\Controller\RestApiController;

class TestObject extends RestApiController
{

    /**
     * Get API Schema object with identifier.
     *
     * @param mixed $params
     * @throws \Exception
     */
    public function retrieve(...$params)
    {
        if ($params[0] == 1) {
            throw new \Exception("TEST-GET");
        }
    }

    /**
     * Create new Schema Object with posted vars.
     * @param array $params
     * @throws \Exception
     */
    public function create(...$params)
    {
        throw new \Exception("TEST-POST");
    }

    /**
     * Update current object with putted vars.
     *
     * @param mixed $params
     * @throws \Exception
     */
    public function update(...$params)
    {
        if ($params[0] == 1) {
            throw new \Exception("TEST-PUT");
        }
    }

    /**
     * Delete schema object with identifier.
     *
     * @param mixed $params
     * @return mixed
     * @throws \Exception
     */
    public function destroy(...$params)
    {
        if ($params[0] == 1) {
            throw new \Exception("TEST-DELETE");
        }
    }

    /**
     * Get API Schemas objects.
     * HTTP GET.
     * @param array $params
     * @throws \Exception
     */
    public function list(...$params)
    {
        throw new \Exception("TEST-FINDALL");
    }

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param mixed $params
     * @throws \Exception
     */
    public function partialUpdate(...$params)
    {
        if ($params[0] == 1) {
            throw new \Exception("TEST-UPDATE");
        }
    }
}
