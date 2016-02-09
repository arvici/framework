<?php
/**
 * Regexpression Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Regex Assert.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Regex extends Assert
{

    private $regex;

    /**
     * Regex constructor.
     * @param string $regex Your regex. ready for preg_match.
     */
    public function __construct($regex)
    {
        parent::__construct();
        $this->regex = $regex;
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
        return preg_match($this->regex, $data[$field]) != 0;
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
        return "Value in field '{$this->friendlyName}' may only comply to the given statments, please look at the help text for information about the field";
    }
}