<?php
/**
 * ParameterCollection.php Description
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Collections;


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