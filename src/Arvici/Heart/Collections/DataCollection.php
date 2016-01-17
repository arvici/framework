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
 * @template <T> The type of the indicidual elements.
 *
 * @package Arvici\Heart\Http\Request
 */
class DataCollection implements \IteratorAggregate, \ArrayAccess, \Countable
{

    /**
     * Contents
     *
     * @var array<T> $contents
     */
    protected $contents = array();


    /**
     * Construct Collection
     * @param array<T> $contents
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
     * @return array<T>
     */
    public function values()
    {
        return array_values($this->contents);
    }

    /**
     * Get the whole raw array
     *
     * @return array<T>
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
     * @return DataCollection<T>
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
     * @return DataCollection<T> $this
     */
    public function replace(array $array = array())
    {
        $this->contents = $array;

        return $this;
    }

    /**
     * Merge current content with given array
     *
     * @param array<T> $secondArray
     * @return DataCollection<T>
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
     * @return DataCollection<T>
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
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Iterator<T> Iterator
     *
     * @codeCoverageIgnore
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contents);
    }

    /**
     * Whether a offset exists
     * @param string $key
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     *
     * @codeCoverageIgnore
     */
    public function offsetExists($key)
    {
        return $this->exists($key);
    }

    /**
     * Offset to retrieve
     * @param string $key
     * @return mixed Can return all value types.
     *
     * @codeCoverageIgnore
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Offset to set
     * @param string $key
     * @param mixed $value
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Offset to unset
     * @param string $key
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
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
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function __unset($name)
    {
        $this->remove($name);
    }
}