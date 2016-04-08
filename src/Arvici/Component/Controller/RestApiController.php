<?php
/**
 * Api Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\Controller;

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
     * HTTP GET.
     *
     * @param mixed $identifier
     */
    abstract public function find($identifier);

    /**
     * Get API Schemas objects.
     * HTTP GET.
     */
    abstract public function findAll();

    /**
     * Create new object.
     * HTTP POST.
     */
    abstract public function create();

    /**
     * Update (replace) (HTTP PUT)
     *
     * @param mixed $identifier
     */
    abstract public function replace($identifier);

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param mixed $identifier
     */
    abstract public function update($identifier);

    /**
     * Delete schema object with identifier.
     * HTTP DELETE.
     *
     * @param mixed $identifier
     */
    abstract public function delete($identifier);
}
