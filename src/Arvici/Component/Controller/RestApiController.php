<?php
/**
 * Api Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component\Controller;

use Arvici\Exception\Response\HttpException;
use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Database;

/**
 * Api Controller
 * @package Arvici\Component\Controller
 */
abstract class RestApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get connection.
     *
     * @param string $connectionName
     *
     * @return Connection
     *
     * @codeCoverageIgnore
     */
    protected function database($connectionName = 'default')
    {
        return Database::connection($connectionName);
    }

    /**
     * Get API Schema object with identifier.
     * HTTP GET on a single entry/detail, retrieve object.
     *
     * @param array ...$params Parameters
     */
    abstract public function retrieve(...$params);

    /**
     * Get API Schemas objects.
     * HTTP GET on a list of objects.
     *
     * @param array ...$params Parameters
     */
    abstract public function list(...$params);

    /**
     * Create new object.
     * HTTP POST on the root url to create new object.
     *
     * @param array ...$params Parameters
     */
    abstract public function create(...$params);

    /**
     * Update (full update) (HTTP PUT)
     *
     * @param array ...$params Parameters
     */
    abstract public function update(...$params);

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param array ...$params Parameters
     */
    abstract public function partialUpdate(...$params);

    /**
     * Delete schema object with identifier.
     * HTTP DELETE.
     *
     * @param array ...$params Parameters
     */
    abstract public function destroy(...$params);

    /**
     * Dispatch the API routes.
     *
     * @param array ...$params
     * @return mixed
     * @throws HttpException
     */
    public function dispatch(...$params)
    {
        // Verify if the method is 'allowed' in our own context.
        $allowedMethods = $this->getAllowedMethods();
        if ($allowedMethods !== null && ! in_array($this->request->method(), $allowedMethods)) {
            // Not allowed. Return to sender.
        }

        $apiMethod = $this->route->getValue('api_method', 'list');
        return call_user_func_array(array($this, $apiMethod), $params);
    }
}
