<?php
/**
 * Alphanumeric Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Alphanumeric Assert.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Alphanumeric extends Assert
{
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
        return preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $data[$field]) != 0;
    }
}