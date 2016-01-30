<?php
/**
 * QueryBuilder
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;
use Arvici\Exception\QueryBuilderException;
use Arvici\Exception\QueryException;
use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Query\Part\Column;

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
}