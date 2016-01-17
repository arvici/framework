<?php
/**
 * ParameterCollection
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Collections;

use Traversable;

/**
 * Paremeter Collection
 *
 * @package Arvici\Heart\Http\Request
 */
class DataCollection implements \IteratorAggregate, \ArrayAccess, \Countable
{

    /**
     * Contents
     *
     * @var array $contents
     */
    protected $contents = array();


    /**
     * Construct Collection
     * @param array $contents
     */
    public function __construct(array $contents = array())
    {
        $this->contents = $contents;
    }


    /**
     * Get Keys as an array
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->contents);
    }

    /**
     * Get values only (strip the keys)
     *
     * @return array
     */
    public function values()
    {
        return array_values($this->contents);
    }

    /**
     * Get the whole raw array
     *
     * @return array
     */
    public function all()
    {
        return $this->contents;
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed $value
     * @return DataCollection
     */
    public function set($key, $value)
    {
        $this->contents[$key] = $value;
        return $this;
    }

    /**
     * Get value for key
     *
     * @param string $key
     * @param mixed $default Default value returned when key doesn't exists.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->contents[$key])) {
            return $this->contents[$key];
        }
        return $default;
    }

    /**
     * Does the key exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($this->contents[$key]);
    }

    /**
     * Remove key in array.
     *
     * @param string $key
     */
    public function remove($key)
    {
        unset($this->contents[$key]);
    }

    /**
     * Replace all values.
     *
     * @param array $array
     * @return DataCollection $this
     */
    public function replace(array $array = array())
    {
        $this->contents = $array;

        return $this;
    }

    /**
     * Merge current content with given array
     *
     * @param array $secondArray
     * @return DataCollection
     */
    public function merge(array $secondArray)
    {
        $this->contents = array_merge($this->contents, $secondArray);

        return $this;
    }

    /**
     * Is the collection empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->contents);
    }

    /**
     * Clear collection
     *
     * @return DataCollection
     */
    public function clear()
    {
        $this->contents = array();

        return $this;
    }


    /**
     * === Magic Methods
     */

    /**
     * Magic set method
     *
     * @see set()
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Magic get method
     *
     * @see get()
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contents);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param string $key <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($key)
    {
        return $this->exists($key);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param string $key <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param string $key <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param string $key <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->contents);
    }

    /**
     * Magic isset method
     *
     * @see exists()
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->exists($name);
    }

    /**
     * Magic unset method
     *
     * @see remove()
     * @param string $name
     */
    public function __unset($name)
    {
        $this->remove($name);
    }
}