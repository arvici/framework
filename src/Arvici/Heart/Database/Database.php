<?php
/**
 * Database
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Database;

use Arvici\Exception\ConfigurationException;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Log\DoctrineLogBridge;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Proxy\Autoloader;
use Doctrine\ORM\EntityManager;

/**
 * Database Heart Component
 *
 * @package Arvici\Heart\Database
 */
class Database
{
    const FETCH_ASSOC   = 2;
    const FETCH_OBJECT  = 5;
    const FETCH_CLASS   = 8;
    const FETCH_NUMERIC = 3;

    const TYPE_NULL     = 0;
    const TYPE_INT      = 1;
    const TYPE_STR      = 2;
    const TYPE_LOB      = 3;
    const TYPE_STMT     = 4;
    const TYPE_BOOL     = 5;

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
     * Get entity manager for the connection and apps.
     *
     * @param string $name
     * @return EntityManager
     */
    public static function entityManager($name = 'default')
    {
        $databaseConnection = self::connection($name)->getDbalConnection();

        if (Configuration::get('app.env') == 'development') {
            $cache = new ArrayCache();
        } else {
            $cache = new FilesystemCache(Configuration::get('app.cache'));
        }

        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl($cache);
        $driver = $config->newDefaultAnnotationDriver([APPPATH . 'Entities'], false);

        $config->setMetadataDriverImpl($driver);
        $config->setQueryCacheImpl($cache);

        $proxyDir = APPPATH . 'Proxies';
        $proxyNamespace = 'App\Proxies';

        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace($proxyNamespace);
        Autoloader::register($proxyDir, $proxyNamespace);

        if (Configuration::get('app.env') == 'development') {
            $config->setAutoGenerateProxyClasses(true);
            $logger = new DoctrineLogBridge(\Logger::getInstance()->getMonologInstance());
            $config->setSQLLogger($logger);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        return EntityManager::create($databaseConnection, $config);
    }

    /**
     * Get driver instance for connection name or driver name.
     *
     * @param string $name Connection name.
     * @param string $driverName Driver name.
     *
     * @return Driver
     */
    public static function driver($name = 'default', $driverName = null)
    {
        if ($name !== null && $driverName === null) {
            $databaseConfig = self::getConnectionConfiguration($name);
            $driverName = $databaseConfig['driver'];
        }

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
        $databaseConfig = Configuration::get('database.connections');
        if (isset($databaseConfig[$name])) {
            $databaseConfig = $databaseConfig[$name];
        } else {
            $databaseConfig = null;
        }

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

    /**
     * Get default return type (fetch mode)
     *
     * @return int
     */
    public static function defaultReturnType()
    {
        return Configuration::get('database.fetchType', self::FETCH_ASSOC);
    }

    /**
     * Normalize fetch type to a real one ;).
     *
     * @param int|null $returnType
     *
     * @return int
     */
    public static function normalizeFetchType($returnType)
    {
        if ($returnType === null) {
            return self::defaultReturnType();
        }
        return $returnType;
    }


    /**
     * Get type of value.
     *
     * @param mixed $value
     * @return int
     */
    public static function typeOfValue($value)
    {
        if (is_null($value)) {
            return self::TYPE_NULL;
        }
        if (is_int($value)) {
            return self::TYPE_INT;
        }
        if (is_bool($value)) {
            return self::TYPE_BOOL;
        }
        return self::TYPE_STR;
    }
}
