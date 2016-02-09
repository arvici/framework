<?php
/**
 * Url Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Url Assert.
 * Be aware, not providing http(s):// is still valid!
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Url extends Assert
{
    /**
     * Url Assert.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute assert on the field, in the data provided.
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
        return filter_var($data[$field], FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get string with error information.
     *
     * @return string
     */
    public function __toString()
    {
        return "Value in field '{$this->friendlyName}' may only contain a valid URL/link";
    }
}