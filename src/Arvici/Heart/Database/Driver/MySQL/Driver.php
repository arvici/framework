<?php
/**
 * MySQL Driver
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Driver\MySQL;
use Arvici\Exception\DatabaseDriverException;

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
     *
     * @throws DatabaseDriverException
     */
    public function connect(array $config, $username = null, $password = null, array $options = array())
    {
        // Validate config
        if (! isset($config['host'], $config['database'])) {
            throw new DatabaseDriverException("No 'host' or 'database' given in the configuration of the connection!");
        }

        $port = isset($config['port']) ? $config['port'] : 3306;
        $charset = isset($config['charset']) ? $config['charset'] : 'utf8';

        // Prepare dsn.
        $dsn = "mysql:host={$config['host']};port=$port;dbname={$config['database']};charset=$charset";

        // Connect
        $connection = new Connection($dsn, $username, $password, $options);

        return $connection;
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
     * Get the driver code. Will be a unique string for every driver.
     *
     * @return string
     */
    public function getCode()
    {
        return "MySQL";
    }
}