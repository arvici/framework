<?php
/**
 * Csrf Token Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Http\Csrf;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Csrf Token.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 *
 * Can't be tested with $_SESSION in PhpUnit.
 * @codeCoverageIgnore
 */
class CsrfToken extends Assert
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
    public function execute (&$data, $field, $options = array())
    {
        if (! isset($data[$field])) return false;
        $name = 'csrfToken';
        if (isset($this->preferences['name']) && is_string($this->preferences['name']))
            $name = $this->preferences['name'];

        $token = $data[$field];

        return Csrf::validateToken($name, $token);
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
        return "Your unique token to submit the form is invalid, please refresh the form and try again!";
    }
}
