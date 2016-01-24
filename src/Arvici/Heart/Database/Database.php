<?php
/**
 * Database
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database;
use Arvici\Exception\ConfigurationException;
use Arvici\Heart\Config\Configuration;

/**
 * Database Heart Component
 *
 * @package Arvici\Heart\Database
 */
class Database
{
    const FETCH_ASSOC   = 1;
    const FETCH_OBJECT  = 2;
    const FETCH_CLASS   = 3;
    const FETCH_NUMERIC = 4;

    /** @var Driver[] $drivers Indexed by driver name! */
    private static $drivers = array();

    /** @var Connection[] $connections Indexed by connection name! */
    private static $connections = array();

    /**
     * Get connection instance for connection name.
     *
     * @param string $name
     *
     * @return Connection
     *
     * @throws ConfigurationException
     */
    public static function connection($name = 'default')
    {
        $databaseConfig = self::getConnectionConfiguration($name);

        /** @var Driver $driver */
        $driver = self::driver($name);

        $username = isset($databaseConfig['username']) ? $databaseConfig['username'] : null;
        $password = isset($databaseConfig['password']) ? $databaseConfig['password'] : null;
        $options = isset($databaseConfig['options']) ? $databaseConfig['options'] : array();

        // Create connection if we don't have it already
        if (! isset(self::$connections[$name])) {
            $connection = $driver->connect($databaseConfig, $username, $password, $options);

            self::$connections[$name] = $connection;
        }

        return self::$connections[$name];
    }

    /**
     * Get driver instance for connection name.
     *
     * @param string $name Connection name.
     *
     * @return Driver
     */
    public static function driver($name = 'default')
    {
        $databaseConfig = self::getConnectionConfiguration($name);
        $driverName = $databaseConfig['driver'];

        // Make the driver if we don't have it already this run.
        if (! isset(self::$drivers[$driverName])) {
            // We will make the driver class first, prepare the full class name.
            $driverClass = new \ReflectionClass("\\Arvici\\Heart\\Database\\Driver\\$driverName\\Driver");

            self::$drivers[$driverName] = $driverClass->newInstance();
        }

        return self::$drivers[$driverName];
    }

    /**
     * Get driver configuration (and validate basics).
     *
     * @param string $name Connection name
     *
     * @throws ConfigurationException
     *
     * @return array
     */
    private static function getConnectionConfiguration($name)
    {
        $databaseConfig = Configuration::get('database.' . $name);
        if ($databaseConfig === null) {
            throw new ConfigurationException("Database configuration for connection '" . $name . "' doesn't exists!");
        }

        if (! isset($databaseConfig['driver'])) {
            throw new ConfigurationException("Database configuration for connection '" . $name . "' is invalid!");
        }

        return $databaseConfig;
    }

    /**
     * Clear all driver connections
     */
    public static function clear()
    {
        self::$drivers = array();
    }
}