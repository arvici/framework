<?php
/**
 * Statement
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database;
use Arvici\Exception\DatabaseException;

/**
 * Prepared Statement.
 *
 * @package Arvici\Heart\Database
 */
interface Statement
{
    /**
     * Bind value to the prepared statement. Could be a positioned or named placeholder (:: or ?).
     *
     * @param mixed $param
     * @param mixed $value
     * @param int $type
     *
     * @return bool
     */
    public function bindValue($param, $value, $type = null);

    /**
     * Bind PHP variable by REFERENCE!.
     *
     * @param mixed $column
     * @param mixed $variable
     * @param int $type
     *
     * @throws \PDOException
     * @throws DatabaseException
     *
     * @return bool
     */
    public function bindParam($column, &$variable, $type = null);

    /**
     * Execute the prepared statement.
     *
     * @param array|null $params
     *
     * @return bool
     */
    public function execute(array $params = null);

    /**
     * Count the affected rows by last insert update or delete statement.
     *
     * @return int
     */
    public function rowCount();

    /**
     * Set the fetch mode.
     *
     * @param int $fetchStyle
     * @param string|null $params Additional parameter
     *
     * @return mixed
     */
    public function setFetchMode($fetchStyle, $params = null);

    /**
     * Fetch single row.
     *
     * @param int $fetchStyle Fetch style to override global.
     *
     * @throws \Exception
     * @throws DatabaseException
     *
     * @return mixed
     */
    public function fetch($fetchStyle = null);

    /**
     * Fetch multiple rows.
     *
     * @param int $fetchStyle Fetch style to override global.
     *
     * @throws \Exception
     * @throws DatabaseException
     *
     * @return array
     */
    public function fetchAll($fetchStyle = null);

    /**
     * Close the current cursor to the statement.
     *
     * @throws DatabaseException
     * @throws \Exception
     *
     * @return bool
     */
    public function closeCursor();
}