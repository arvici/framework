<?php
/**
 * Query
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;

use Arvici\Heart\Database\Driver;
use Arvici\Heart\Database\Query\Part\Column;

/**
 * Query Data Class.
 * @package Arvici\Heart\Database\Query
 */
class Query
{
    const MODE_NONE     = 0;

    const MODE_SELECT   = 1;
    const MODE_INSERT   = 2;
    const MODE_UPDATE   = 3;
    const MODE_DELETE   = 4;
    const MODE_TRUNCATE = 5;
    const MODE_ADVANCED = 99;

    /**
     * Query constructor.
     *
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
        $this->mode = self::MODE_NONE;
    }

    /**
     * Driver (and the language level) we use for the building.
     *
     * @var Driver
     */
    public $driver;

    /**
     * Mode of the query.
     *
     * @var int Uses one of the constants.
     */
    public $mode;


    /**
     * Select part of query. Contains Column parts.
     *
     * @var Column[]
     */
    public $select = array();

    /**
     * Select Table part of query. Contains all Table parts.
     *
     * @var Part[]
     */
    public $table = array();
}