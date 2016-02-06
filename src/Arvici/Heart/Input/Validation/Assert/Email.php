<?php
/**
 * Email Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Email Assert
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Email extends Assert
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

        $email = $data[$field];
        if (function_exists('idn_to_ascii')) $email = idn_to_ascii($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}