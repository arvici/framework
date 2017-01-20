<?php
/**
 * Api Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
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
     * @param array $identifiers
     */
    abstract public function find($identifiers);

    /**
     * Get API Schemas objects.
     * HTTP GET.
     *
     * @param array $identifiers
     */
    abstract public function findAll($identifiers = array());

    /**
     * Create new object.
     * HTTP POST.
     *
     * @param array $identifiers
     */
    abstract public function create($identifiers = array());

    /**
     * Update (replace) (HTTP PUT)
     *
     * @param array $identifiers
     */
    abstract public function replace($identifiers);

    /**
     * Update (update) (HTTP PATCH)
     *
     * @param array $identifiers
     */
    abstract public function update($identifiers);

    /**
     * Delete schema object with identifier.
     * HTTP DELETE.
     *
     * @param array $identifiers
     */
    abstract public function delete($identifiers);
}
