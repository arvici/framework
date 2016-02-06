<?php
/**
 * Collection of asserts.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Collection of asserts.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Collection extends DataCollection
{
    public function __construct($content = array())
    {
        parent::__construct($content);
    }

    /**
     * Execute All Asserts
     *
     * @param array $data
     * @param string $field
     * @param array $options
     * @return bool Success?
     */
    public function executeAll(&$data, $field, $options = array())
    {
        // TODO: Implement execute all method.

        return true;
    }
}
