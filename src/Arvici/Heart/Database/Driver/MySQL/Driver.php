<?php
/**
 * MySQL Driver
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Driver\MySQL;

/**
 * MySQL Driver
 *
 * @package Arvici\Heart\Database\Driver\MySQL]
 */
class Driver implements \Arvici\Heart\Database\Driver
{

    /**
     * Create the connection. Will return a connection instance.
     *
     * @param array $config Specific configuration for the driver to establish connection and maintain it.
     * @param string|null $username Username to connect, could be optional. Driver specific.
     * @param string|null $password Password to connect, could be optional. Driver specific.
     * @param array $options Driver options apply to the connection and driver itself.
     *
     * @return \Arvici\Heart\Database\Connection
     */
    public function connect(array $config, $username = null, $password = null, array $options = array())
    {
        // TODO: Implement connect() method.
    }

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getName()
    {
        return "MySQL PDO Driver";
    }

    /**
     * Get database currently connected to. Could be null if driver doesn't support this.
     *
     * @return string|null
     */
    public function getDatabase()
    {
        // TODO: Implement getDatabase() method.
    }

    /**
     * Get the driver code. Will be a unique string for every driver.
     *
     * @return string
     */
    public function getCode()
    {
        return "MySQL";
    }
}