<?php
/**
 * Cache Pool Wrapper.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Cache;

use Arvici\Heart\Config\Configuration;
use Stash\Interfaces\DriverInterface;
use Stash\Pool as BasePool;


/**
 * Pool Extension of the Stash package.
 * With this wrapper we will be able to inject some framework related information, like logging.
 *
 * @package Arvici\Heart\Cache
 */
class Pool extends BasePool
{
    /** @var string|null */
    protected $name;

    /**
     * Pool constructor.
     *
     * @param DriverInterface|null $driver
     * @param string|null $name
     */
    public function __construct(DriverInterface $driver = null, $name = null)
    {
        parent::__construct($driver);
        $this->name = $name;

        // Inject the framework logger.
        $this->logger = \Logger::getInstance();

        // Set disabled state.
        $isEnabled = Configuration::get('cache.enabled', false);
        $isProduction = Configuration::get('app.env') === 'production';
        $isForceEnabled = Configuration::get('cache.forceEnabled', false);

        if (! $isEnabled || ($isEnabled && ! $isProduction && ! $isForceEnabled)) {
            $this->isDisabled = true; // @codeCoverageIgnore
        }
    }

    /**
     * Get name of pool.
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
