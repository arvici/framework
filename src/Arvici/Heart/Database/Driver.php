<?php
/**
 * Driver API
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Database;
use Arvici\Exception\DatabaseDriverException;

/**
 * Driver API (Interface)
 * @package Arvici\Heart\Database
 */
interface Driver
{
    /**
     * Create the connection. Will return a connection instance.
     *
     * @param array $config Specific configuration for the driver to establish connection and maintain it.
     * @param string|null $username Username to connect, could be optional. Driver specific.
     * @param string|null $password Password to connect, could be optional. Driver specific.
     * @param array $options Driver options apply to the connection and driver itself.
     *
     * @throws DatabaseDriverException
     * @throws \Exception
     *
     * @return \Arvici\Heart\Database\Connection
     */
    public function connect(array $config, $username = null, $password = null, array $options = array());

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the driver code. Will be a unique string for every driver.
     *
     * @return string
     */
    public function getCode();
}
