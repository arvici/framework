<?php
/**
 * QueryBuilder
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;
use Arvici\Exception\DatabaseException;
use Arvici\Exception\QueryBuilderException;
use Arvici\Exception\QueryBuilderParseException;
use Arvici\Exception\QueryException;
use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Database;
use Arvici\Heart\Database\Query\Part\Column;
use Arvici\Heart\Database\Query\Part\Table;

/**
 * Query Builder
 *
 * @package Arvici\Heart\Database\Query
 */
class QueryBuilder
{
    /** @var Connection */
    private $connection;

    /** @var Query */
    private $query;


    /**
     * Exception to throw at the last execute/fetch call.
     *
     * @var \Exception
     */
    private $exception;

    /**
     * QueryBuilder constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        $this->query = new Query($connection->getDriver());
    }

    /**
     * Get the raw query building part holder.
     *
     * WARNING: Don't change anything unless you know what the result can be!.
     *
     * @return Query
     */
    public function getRawQuery()
    {
        return $this->query;
    }



    /**********************************************
     *  SELECT PART - (COLUMN, TABLE, CONDITION)  *
     **********************************************/

    /**
     * Select query.
     *
     * @param array|string|null $column Array of column names, null for every column.
     * You can also provide string with comma separated list of columns, just as you would do
     * with a normal query.
     * When giving array like this:
     *  column => as_name
     * You will make an alias for the selected column!
     *
     * @param bool $replace Replace existing select columns. Default false (no).
     *
     * @throws QueryBuilderException
     *
     * @return QueryBuilder
     */
    public function select($column = null, $replace = false)
    {
        // Check if mode is defined. Then stop.
        if ($this->query->mode !== Query::MODE_NONE && $this->query->mode !== Query::MODE_SELECT) {
            $this->exception = new QueryBuilderException("The query mode is already defined!");
            return $this;
        }

        // Parse when string
        if (is_string($column)) {
            if (strstr($column, ',')) {
                // Explode
                $column = explode(',', $column);
            } elseif ($column === '*') {
                $column = null;
            } else {
                $column = array($column);
            }
        }

        // Replace
        if ($replace) {
            $this->query->select = array();
        }

        // When * is given
        if ($column === null) {
            // Ignore the select here, we will just add the * to our stack.
            $part = new Column("*");
            $part->appendQuery($this->query);

            $this->query->mode = Query::MODE_SELECT;
            return $this;
        }

        // Invalid type?
        if (! is_array($column)) {
            $this->exception = new QueryException("Select column should be string or array!");
            return $this;
        }

        // Append to current select.
        foreach ($column as $key => $value) {

            $columnName = null;
            $columnAs = null;

            if (is_string($key)) {
                $columnName = trim($key);
                $columnAs = trim($value);
            }else{
                $columnName = trim($value);
            }

            $columnPart = new Column($columnName);
            $columnPart->setColumnAs($columnAs);

            $columnPart->appendQuery($this->query);
        }

        // Set mode and return self.
        $this->query->mode = Query::MODE_SELECT;
        return $this;
    }


    /**
     * Select from table(s).
     *
     * @param string|array $table Give a single table, multiple tables with following syntax:
     * - Just value => is the actual table name
     * - Key is table name and Value is alias.
     *
     * Could also be a comma separated string.
     *
     * @param bool $replace Replace existing tables?
     *
     * @throws QueryBuilderException
     *
     * @return QueryBuilder
     */
    public function from($table, $replace = false)
    {
        if (   $this->query->mode !== Query::MODE_NONE
            && $this->query->mode !== Query::MODE_SELECT
            && $this->query->mode !== Query::MODE_DELETE) {

            $this->exception = new QueryBuilderException("The query mode is already defined! Or should be SELECT, DELETE for table();");
            return $this;
        }

        return $this->table($table, $replace);
    }








    /**********************************************
     *  GENERAL PART                              *
     **********************************************/


    /**
     * Adjust table to select/update or do anything with.
     *
     * @param string|array $table Give a single table, multiple tables with following syntax:
     * - Just value => is the actual table name
     * - Key is table name and Value is alias.
     *
     * Could also be a comma separated string.
     *
     * @param bool $replace Replace existing tables?
     *
     * @throws QueryBuilderException
     *
     * @return QueryBuilder
     */
    public function table($table, $replace = false)
    {
        // Check if mode is defined. Then stop.
        if (   $this->query->mode !== Query::MODE_NONE
            && $this->query->mode !== Query::MODE_SELECT
            && $this->query->mode !== Query::MODE_DELETE
            && $this->query->mode !== Query::MODE_UPDATE
            && $this->query->mode !== Query::MODE_TRUNCATE
            && $this->query->mode !== Query::MODE_INSERT
            && $this->query->mode !== Query::MODE_ADVANCED) {

            $this->exception = new QueryBuilderException("The query mode is already defined or doesn't complain the table constraints"); // @codeCoverageIgnore
            return $this; // @codeCoverageIgnore
        }

        // Parse when string
        if (is_string($table)) {
            if (strstr($table, ',')) {
                // Explode
                $table = explode(',', $table);
            } else {
                $table = array($table);
            }
        }

        // Replace
        if ($replace) {
            $this->query->table = array();
        }

        // Invalid type?
        if (! is_array($table)) {
            $this->exception = new QueryException("Select table should be string or array!");
            return $this;
        }

        // Append to current select.
        foreach ($table as $key => $value) {

            $tableName = null;
            $tableAs =   null;

            if (is_string($key)) {
                $tableName = trim($key);
                $tableAs = trim($value);
            }else{
                $tableName = trim($value);
            }

            $tablePart = new Table($tableName);
            $tablePart->setTableAs($tableAs);

            $tablePart->appendQuery($this->query);
        }

        // Return self.
        return $this;
    }





    /**********************************************
     *  EXECUTORS                                 *
     **********************************************/


    /**
     * Fetch all records with the building query.
     *
     * @param null|int $mode Optional fetch mode.
     * @param null|string $class Optional fetch class.
     *
     * @return array
     */
    public function fetchAll($mode = null, $class = null)
    {
        return $this->doExecute(true, $mode, $class);
    }


    /**
     * Fetch single record with the building query.
     *
     * @param null|int $mode Optional fetch mode.
     * @param null|string $class Optional fetch class.
     *
     * @return mixed|null
     */
    public function fetchSingle($mode = null, $class = null)
    {
        return $this->doExecute('single', $mode, $class);
    }


    /**
     * Execute build query, Don't return fetched results back.
     *
     * @throws QueryBuilderException
     * @throws QueryException
     * @throws \Exception
     *
     * @return bool success
     */
    public function execute()
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->doExecute(false);
    }









    /**********************************************
     *  PRIVATE METHODS                           *
     **********************************************/


    /**
     * Execute the query, fetch if provided details to do so.
     *
     * @param bool $fetch       Fetch on or off?
     * @param null $fetchMode   Fetch mode (null for default)
     * @param null $fetchClass  Fetch class (null for none)
     *
     * @throws QueryBuilderParseException
     * @throws QueryBuilderException
     * @throws QueryException
     * @throws DatabaseException
     *
     * @throws \Exception
     *
     * @return bool Boolean when fetching is off.
     * @return array|object|null Result when fetching is on.
     */
    private function doExecute($fetch = false, $fetchMode = null, $fetchClass = null)
    {
        if ($fetch) {
            $fetchMode = Database::normalizeFetchType($fetchMode);
        }

        // Parse SQL and Bind
        $sql = $this->query->getSQL();
        $bind = $this->query->getBind();

        // Connection and execute
        $statement = $this->connection->prepare($sql);

        foreach ($bind as $idx => $value) {
            $statement->bindValue(($idx+1), $value, Database::typeOfValue($value));
        }

        // Fetch
        if ($fetch) {
            if ($fetchMode === Database::FETCH_CLASS) {
                // Verify class
                new \ReflectionClass($fetchClass);

                $statement->setFetchMode($fetchMode, $fetchClass);
            } else {
                $statement->setFetchMode($fetchMode);
            }
        }

        // Execute
        $success = $statement->execute();
        if (! $success) {
            return false;
        }

        if ($fetch && $fetch === 'single') {
            return $statement->fetch();
        }
        if ($fetch) {
            return $statement->fetchAll();
        }

        return true;
    }

}