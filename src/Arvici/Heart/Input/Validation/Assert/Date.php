<?php
/**
 * Date Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;


use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Date Assert
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Date extends Assert
{
    private $format;

    /**
     * Validate Date, optional with format.
     * @param string $format Format to match, NULL for only verify if the date could be parsable with `strtotime()`.
     */
    public function __construct($format = null)
    {
        parent::__construct();
        $this->format = $format;
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

        $time = strtotime($data[$field]);

        if ($this->format !== null && $time !== false) {
            // Check format.
            $expect = date($this->format, $time);
            return $data[$field] === $expect;
        }

        return $time !== false;
    }



    /**
     * Get string with error information.
     *
     * @return string
     */
    public function __toString()
    {
        return "Value in field '{$this->friendlyName}' may only contain a valid date in specific format";
    }
}