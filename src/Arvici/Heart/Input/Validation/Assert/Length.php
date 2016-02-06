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
        if (! is_array($this->conditions) && is_numeric($this->conditions)) {
            $this->conditions = array('min' => $this->conditions);
        }

        if (! isset($data[$field])) return false;

        if (isset($this->conditions['min'], $this->conditions['max'])) {
            return ($data[$field] >= $this->conditions['min'] && $data[$field] <= $this->conditions['max']);
        }
        if (isset($this->conditions['min'])) {
            return ($data[$field] >= $this->conditions['min']);
        }
        if (isset($this->conditions['max'])) {
            return ($data[$field] <= $this->conditions['max']);
        }
        return false;
    }

}