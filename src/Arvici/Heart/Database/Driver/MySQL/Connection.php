<?php
/**
 * MySQL Connection
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Driver\MySQL;

use Arvici\Heart\Database\Database;
use Arvici\Heart\Database\Driver\PDOConnection;

/**
 * MySQL Connection.
 *
 * @package Arvici\Heart\Database\Driver\MySQL
 */
class Connection extends PDOConnection
{
    /**
     * Get driver instance for this connection.
     *
     * @return \Arvici\Heart\Database\Driver
     */
    public function getDriver()
    {
        return Database::driver(null, 'MySQL');
    }
}