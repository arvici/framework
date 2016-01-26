<?php
/**
 * Query
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;

use Arvici\Heart\Database\Driver;

/**
 * Query Data Class.
 * @package Arvici\Heart\Database\Query
 */
class Query
{
    /**
     * Query constructor.
     *
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Driver (and the language level) we use for the building.
     *
     * @var Driver
     */
    public $driver;


    /**
     * Select part of query. Contains Column parts.
     *
     * @var Part[]
     */
    public $select;

    /**
     * Select Table part of query. Contains all Table parts.
     *
     * @var Part[]
     */
    public $table;
}