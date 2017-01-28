<?php
/**
 * Table Schema Definition
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Database\Schema\Definition;


/**
 * Table Schema definition object.
 *
 * @package Arvici\Heart\Database\Schema\Definition
 */
abstract class Table extends Base
{
    public function __construct()
    {
        parent::__construct();
    }
}
