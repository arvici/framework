<?php
/**
 * Alpha Assert
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Class Alpha Assert.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Alpha extends Regex
{
    /**
     * Alpha Assert.
     * @param bool $dash Dash allowed? Default false.
     * @param bool $underscore Underscore allowed? Default false.
     */
    public function __construct($dash = false, $underscore = false)
    {
        $regex = '/^[a-zA-Z' . ($dash ? '-' : '') . ($underscore ? '_' : '') . ']+$/';
        parent::__construct($regex);
    }
}