<?php
/**
 * QueryBuilder
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;
use Arvici\Heart\Database\Connection;

/**
 * Query Builder
 *
 * @package Arvici\Heart\Database\Query
 */
class QueryBuilder
{
    /** @var Connection */
    private $connection;

    /**
     * QueryBuilder constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}