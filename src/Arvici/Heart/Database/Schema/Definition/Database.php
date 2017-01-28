<?php
/**
 * Database Schema Definition
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Database\Schema\Definition;


/**
 * Database Schema Definition.
 *
 * @package Arvici\Heart\Database\Schema\Definition
 */
abstract class Database extends Base
{
    public function __construct()
    {
        parent::__construct();
    }
}
