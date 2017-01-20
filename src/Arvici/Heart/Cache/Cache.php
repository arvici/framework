<?php
/**
 * Cache Heart Manager.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Cache;

use Arvici\Exception\NotFoundException;
use Arvici\Heart\Config\Configuration;
use Stash\Interfaces\DriverInterface;


class Cache
{
    /**
     * Hold a list of defined pools. The name of the pool is used as a key.
     * The value is an instance of the Pool class.
     *
     * @var array
     */
    private $pools = [];

    /**
     * Holds configuration of the pools.
     * @var mixed|null
     */
    private $configuration = [];



    /**
     * Store singleton.
     * @var Cache|null
     */
    private static $instance = null;

    /**
     * Get singleton instance of cache manager.
     * @return Cache
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    private function __construct()
    {
        // Get configuration
        $this->configuration = Configuration::get('cache.*', []);

        // Initiate pools
        $this->initiatePools();
    }

    /**
     * Get pool instance.
     *
     * @param string $name
     * @return Pool
     *
     * @throws NotFoundException
     */
    public function getPool ($name = 'default')
    {
        if (isset($this->pools[$name])) {
            return $this->pools[$name];
        }
        throw new NotFoundException('Caching Pool not found with given name \''.$name.'\'');
    }


    /**
     * Initiate Pools.
     */
    protected function initiatePools ()
    {
        foreach ($this->configuration['pools'] as $name => $poolConfiguration) {
            // Try to get the driver class. Will throw an exception if not exists.
            new \ReflectionClass($poolConfiguration['driver']);

            $options = $poolConfiguration['options'];

            /** @var DriverInterface $driverInstance */
            $driverInstance = new $poolConfiguration['driver']($options);

            $poolInstance = new Pool($driverInstance);

            // Register in the local instance holding variable.
            $this->pools[$name] = $poolInstance;
        }
    }
}
