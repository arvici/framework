<?php
/**
 * Contain Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Contain - Value should contain specific string.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Contain extends Assert
{

    /**
     * Contain constructor.
     *
     * @param array|string $conditions Contains the following items. Could be a single string too.
     * @param bool $matchCase Match case? Default false.
     */
    public function __construct($conditions, $matchCase = false)
    {
        parent::__construct($conditions, array('case' => $matchCase));
    }

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
        if (! isset($data[$field])) return false;
        if ($this->conditions === null) return false;
        if (! is_array($this->conditions) && is_string($this->conditions)) {
            $this->conditions = array($this->conditions);
        }

        $valid = false;

        foreach ($this->conditions as $contain) {
            if ($this->contains($data[$field], $contain)) {
                $valid = true;
            }
        }

        return $valid;
    }

    /**
     * @param $haystack
     * @param $search
     * @return bool
     */
    private function contains($haystack, $search)
    {
        if ($this->preferences['case']) {
            return strstr($haystack, $search) !== false;
        }
        return stristr($haystack, $search) !== false;
    }

}