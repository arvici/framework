<?php
/**
 * Query Part
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;
use Arvici\Exception\QueryException;
use Arvici\Heart\Database\Statement;

/**
 * Query Part public interface.
 *
 * @package Arvici\Heart\Database\Query
 */
interface Part
{
    /**
     * Get part name. Used for building and combining together.
     *
     * @return mixed
     */
    public function getName();

    /**
     * Append to query.
     *
     * @param $query
     *
     * @return bool
     *
     * @throws QueryException
     */
    public function appendQuery(Query &$query);

    /**
     * Generate Query String for the parts given.
     *
     * @param Part[] $parts
     *
     * @return string
     *
     * @throws QueryException
     */
    public static function generateQueryString(array $parts);

    /**
     * Apply generated to prepared statement, use for binding values!
     *
     * @param Statement $statement
     *
     * @return bool
     *
     * @throws QueryException
     */
    public static function applyQueryToStatement(Statement &$statement);
}