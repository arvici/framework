<?php
/**
 * Configuration Description
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Config;

/**
 * Configuration Class.
 *
 * @package Arvici\Heart\Config
 *
 * @api
 */
class Configuration implements \ArrayAccess
{
    /** @var Configuration */
    private static $instance = null;

    private $config = array();

    /**
     * Configuration constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        if (self::$instance !== null) {
            throw new \Exception("Use the static functions on the configuration class!");
        }
    }


    /**
     * Get instance of Configuration class.
     * @return Configuration
     */
    private static function getInstance()
    {
        if (self::$instance === null) { // @codeCoverageIgnore
            self::$instance = new self(); // @codeCoverageIgnore
        } // @codeCoverageIgnore
        return self::$instance;
    }

    /**
     * Define multiple configuration keys and values in a closure.
     *
     * @param string $section Section to define.
     * @param \Closure $closure Function (inline) that will return the values.
     *
     * @throws \Exception
     */
    public static function define($section, \Closure $closure)
    {
        $config = call_user_func($closure, self::getInstance());
        if (! is_array($config)) {
            throw new \Exception("You should return an array in the Configuration::define closure!");
        }

        self::getInstance()->add($section, $config);
    }


    /**
     * Set configuration value.
     *
     * @param string $name Should complain with the configuration (dots) format.
     * @param mixed $value Values to set
     *
     * @return bool
     */
    public static function set($name, $value)
    {
        if (! strstr($name, '.')) {
            return false;
        }

        $parts = explode('.', $name, 2);
        $section = $parts[0];
        $key = $parts[1];

        self::getInstance()->add($section, array($key => $value));

        return true;
    }

    /**
     * Get a configuration by key. Use dot notation. Use * to get all section configurations.
     *
     * @param string $name name of key, use dot notation. Use * as a wildcard after the section notation to get
     *                     the whole section
     * @param mixed $default default if value doesn't exists, default null.
     *
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        if (! strstr($name, '.')) {
            return $default;
        }

        // Extract information from key.
        $parts = explode('.', $name, 2);
        $section = $parts[0];
        $key = $parts[1];

        // Get section.
        $data = self::getInstance()->getSection($section);

        // Check if we want all the section variables.
        if ($key === '*') {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * Get the whole set of the configuration section.
     *
     * @param string $section section name.
     * @param mixed $default default value when undefined. Leave undefined to use the build-in defined defaults for the
     *                       section.
     *
     * @return mixed
     *
     * @codeCoverageIgnore ignore due to a coverage bug.
     */
    public function getSection($section, $default = -1)
    {
        // Return if section is defined.
        if (isset($this->config[$section])) {
            return $this->config[$section];
        }
        if ($default !== -1) {
            return $default;
        }

        // Find the default value (in default configuration definitions).
        $defaultClass = new \ReflectionClass(DefaultConfiguration::class);
        return $defaultClass->getStaticPropertyValue($section, null);

    }

    /**
     * Add section data to the configuration.
     *
     * @param string $section
     * @param array $config
     */
    public function add($section, $config = array())
    {
        if (! isset($this->config[$section])) {
            $this->config[$section] = array();
        }

        $this->config[$section] = array_merge($this->config[$section], $config);
    }

    /**
     * Get all configuration.
     *
     * @return array
     */
    public static function all()
    {
        return self::getInstance()->config;
    }

    /**
     * Get section configuration data.
     *
     * @deprecated Since 1.2.0. Will be removed in 2.0.0.
     *
     * @param string $section
     * @return array|null
     *
     * @codeCoverageIgnore
     */
    private function doGetSection($section)
    {
        return isset($this->config[$section]) ? $this->config[$section] : null;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     *
     * @codeCoverageIgnore
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     *
     * @codeCoverageIgnore
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     *
     * @codeCoverageIgnore
     */
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     *
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}
