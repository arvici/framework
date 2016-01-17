<?php
/**
 * ParameterCollection
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Collections;

/**
 * Parameter Collection
 * @package Arvici\Heart\Collections
 *
 * @codeCoverageIgnore
 */
class ParameterCollection extends DataCollection
{
    /**
     * ParameterCollection constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }


}