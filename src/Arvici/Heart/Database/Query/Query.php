<?php
/**
 * Query
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Database\Query;

use Arvici\Exception\QueryBuilderException;
use Arvici\Heart\Database\Driver;
use Arvici\Heart\Database\Query\Part\Column;
use Arvici\Heart\Database\Query\Part\Table;
use Arvici\Heart\Database\Query\Part\WhereGroup;

/**
 * Query Data Class.
 * @package Arvici\Heart\Database\Query
 */
class Query
{
    const MODE_NONE      = 0;

    const MODE_SELECT    = 1;
    const MODE_INSERT    = 2;
    const MODE_UPDATE    = 3;
    const MODE_DELETE    = 4;
    const MODE_TRUNCATE  = 5;
    const MODE_ADVANCED  = 99;

    const STATE_NONE     = 0;
    const STATE_COMPLETE = 1;

    /**
     * @var Parser
     */
    private $parser = null;

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
     * State of the current query.
     *
     * @var int Uses one of the constants.
     */
    public $state;




    /**
     * Select part of query. Contains Column parts.
     *
     * @var Column[]
     */
    public $select = array();

    /**
     * Select Table part of query. Contains all Table parts.
     *
     * @var Table[]
     */
    public $table = array();

    /**
     * Where groups containing where and more groups.
     *
     * @var WhereGroup[]
     */
    public $where = array();





    /**
     * Get SQL query.
     *
     * @return string
     *
     * @throws QueryBuilderException
     */
    public function getSQL()
    {
        return $this->parse()->getSQL();
    }

    /**
     * Get SQL Bind array.
     *
     * @return array
     *
     * @throws QueryBuilderException
     */
    public function getBind()
    {
        return $this->parse()->getBind();
    }

    /**
     * Parse query
     * @throws QueryBuilderException
     *
     * @return Parser
     */
    private function parse()
    {
        if (! $this->state = self::STATE_COMPLETE) {
            throw new QueryBuilderException("Query is not yet completed!");
        }

        if ($this->parser === null) {
            $this->parser = new Parser($this);
        }

        $this->parser->validate();

        return $this->parser;
    }
}
