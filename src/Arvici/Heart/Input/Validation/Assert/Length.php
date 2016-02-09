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

        if (! is_numeric($data[$field])) {
            $length = strlen($data[$field]);
        }else{
            $length = $data[$field];
        }


        if (isset($this->conditions['min'], $this->conditions['max'])) {
            return ($length >= $this->conditions['min'] && $length <= $this->conditions['max']);
        }
        if (isset($this->conditions['min'])) {
            return ($length >= $this->conditions['min']);
        }
        if (isset($this->conditions['max'])) {
            return ($length <= $this->conditions['max']);
        }
        return false;
    }



    /**
     * Get string with error information.
     *
     * @return string
     */
    public function __toString()
    {
        if (is_array($this->conditions)) {
            if (isset ($this->conditions['min'])) {
                if (isset($this->conditions['max'])) {
                    return "Value in field '{$this->friendlyName}' or length of it may only be between {$this->conditions['min']} and {$this->conditions['max']}";
                }
                return "Value in field '{$this->friendlyName}' or length of it may only be minimum of {$this->conditions['min']}";
            }
            if (isset ($this->conditions['max'])) {
                return "Value in field '{$this->friendlyName}' or length of it may only less then {$this->conditions['max']}";
            }
        }
        if (is_numeric($this->conditions)) {
            return "Value in field '{$this->friendlyName}' or length of it must be min. {$this->conditions}";
        }
        return "Value in field '{$this->friendlyName}' must comply with conditions";
    }

}