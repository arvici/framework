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
class Alphanumeric extends Regex
{

    /**
     * Alpha Numeric Assert.
     * @param bool $dash Dash allowed? Default false.
     * @param bool $underscore Underscore allowed? Default false.
     */
    public function __construct($dash = false, $underscore = false)
    {
        $regex = '/^[a-zA-Z0-9' . ($dash ? '-' : '') . ($underscore ? '_' : '') . ']+$/';
        parent::__construct($regex);
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
        return "Value in field '{$this->friendlyName}' may only contain alpha-numeric characters (a-z and 0-9)";
    }
}