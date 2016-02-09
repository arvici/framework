<?php
/**
 * Ip Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Ip Assert.
 * IPv4 + IPv6 validation. Private + public addresses.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Ip extends Assert
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
        return filter_var($data[$field], FILTER_VALIDATE_IP) !== false;
    }



    /**
     * Get string with error information.
     *
     * @return string
     */
    public function __toString()
    {
        return "Value in field '{$this->friendlyName}' may only contain a valid IP address";
    }
}