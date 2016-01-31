<?php
/**
 * Api Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\Controller;
use Arvici\Heart\Database\Database;

/**
 * Api Controller
 * @package Arvici\Component\Controller
 */
abstract class ApiController extends Controller
{
    /**
     * Database connection.
     *
     * @var \Arvici\Heart\Database\Connection
     */
    protected $database;

    public function __construct()
    {
        parent::__construct();

        $this->database = Database::connection();
    }


    /**
     * Get API Schema object with identifier.
     *
     * @param mixed $identifier
     */
    abstract public function get($identifier);

    /**
     * Create new Schema Object with posted vars.
     */
    abstract public function post();

    /**
     * Update current object with putted vars.
     *
     * @param mixed $identifier
     */
    abstract public function put($identifier);

    /**
     * Delete schema object with identifier.
     *
     * @param mixed $identifier
     */
    abstract public function delete($identifier);
}
