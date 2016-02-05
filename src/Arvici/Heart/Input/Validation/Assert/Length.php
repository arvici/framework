<?php
/**
 * Length Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Assert Length - Require min, max or between length.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Length extends Assert
{

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
        if (! is_array($this->options) && is_numeric($this->options)) {
            $this->options = array('min' => $this->options);
        }

        if (! isset($data[$field])) return false;

        if (isset($this->options['min'], $this->options['max'])) {
            return ($data[$field] >= $this->options['min'] && $data[$field] <= $this->options['max']);
        }
        if (isset($this->options['min'])) {
            return ($data[$field] >= $this->options['min']);
        }
        if (isset($this->options['max'])) {
            return ($data[$field] <= $this->options['max']);
        }
        return false;
    }

}