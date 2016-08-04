<?php
/**
 * InList Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * InList - Check if value is one of the predefined list items.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class InList extends Assert
{

    /**
     * Contain constructor.
     *
     * @param array $conditions List of possible values.
     * @param bool $strict Strict mode, also check type? Default false.
     */
    public function __construct($conditions, $strict = false)
    {
        parent::__construct($conditions, array('strict' => $strict));
    }

    /**
     * Execute assert.
     *
     * @param array $data Full data array.
     * @param string $field Field Name.
     * @param array $options Optional options given at runtime.
     * @return bool
     *
     * @throws ValidationException
     */
    public function execute(&$data, $field, $options = array())
    {
        if (! isset($data[$field])) return false;
        if ($this->conditions === null) return false;
        return in_array($data[$field], $this->conditions, $this->preferences['strict']);
    }


    /**
     * Get string with error information.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return "Value in field '{$this->friendlyName}' may only be one of the listed items, please look at the help text";
    }
}
