<?php
/**
 * Connection encapsulation for every pdo connection.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Driver;

use Arvici\Exception\DatabaseException;
use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Statement;

/**
 * Connection encapsulation for every pdo connection.
 *
 * @package Arvici\Heart\Database\Driver
 */
class PDOConnection implements Connection
{
    /**
     * Select and return results of executed $query.
     *
     * Will automaticly bind parameters for you, you can use the following binding formats, but not combined!:
     * - :param:     format - Will need string keys on the $bind array!
     * - ?           format - Will need integer keys on the $bind array!
     *
     * Will return in several return types. The return types must be value of the constants of the Database::FETCH_* types.
     * -    FETCH_ASSOC     - default when no configuration is changed!
     * -    FETCH_OBJECT
     * -    FETCH_CLASS     - Will expect you to fill in te other parameter too.
     * -    FETCH_NUMERIC   - Will be fetching the column keys in numeric keys. (in order)
     *
     * @param string $query
     * @param array $bind
     * @param null|int $returnType
     * @param null|string $returnClass
     *
     * @throws DatabaseException
     *
     * @return mixed
     */
    public function select($query, $bind = array(), $returnType = null, $returnClass = null)
    {
        // TODO: Implement select() method.
    }

    /**
     * Insert data into the table. Will make a query for you and prepare the statement with the data.
     *
     * @param string $table
     * @param array $data
     *
     * @throws DatabaseException
     *
     * @return bool Successful inserted?
     */
    public function insert($table, array $data)
    {
        // TODO: Implement insert() method.
    }

    /**
     * Update record found with the $where conditions. Only change the data in $data.
     * Will build a query for you!
     *
     * @param string $table
     * @param array $data Data to update. Key is column name and value is the new value of that column.
     * @param array $where Where, assoc, key is column and value is condition. Will not work with LIKE and LIKE %%!
     *
     * @throws DatabaseException
     *
     * @return bool Successful update executed?
     */
    public function update($table, array $data, array $where)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete record with matching conditions in the $where.
     *
     * @param string $table Table.
     * @param array $where Assoc key(column) conditions.
     *
     * @throws DatabaseException
     *
     * @return bool Successful deleted?
     */
    public function delete($table, array $where)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Truncate table (make it empty).
     * May not be supported by every driver!
     *
     * @param string $table
     *
     * @throws DatabaseException
     *
     * @return bool Successful truncate?
     */
    public function truncate($table)
    {
        // TODO: Implement truncate() method.
    }

    /**
     * Prepare statement.
     *
     * @param string $query
     * @param array $bind
     *
     * @throws DatabaseException
     *
     * @return Statement
     */
    public function prepare($query, $bind = array())
    {
        // TODO: Implement prepare() method.
    }

    /**
     * Get last inserted ID.
     *
     * @param null|string $name
     *
     * @throws DatabaseException
     *
     * @return int
     */
    public function lastInsertedId($name = null)
    {
        // TODO: Implement lastInsertedId() method.
    }
}