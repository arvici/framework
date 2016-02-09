<?php
/**
 * Custom Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Custom Assert with callback.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Custom extends Assert
{
    /** @var callable */
    private $callback;

    /**
     * Custom validator with callback.
     *
     * @param callable $callback Callback gets to parameters, first is value, second is options from validator class.
     */
    public function __construct(callable $callback)
    {
        parent::__construct();

        $this->callback = $callback;
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
        return call_user_func_array($this->callback, [$data[$field], $options]);
    }


    /**
     * Get string with error information.
     *
     * @return string
     */
    public function __toString()
    {
        return "Value in field '{$this->friendlyName}' may only contain validated data, please referrer to the help text for the field";
    }
}