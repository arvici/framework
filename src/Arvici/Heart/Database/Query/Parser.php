<?php
/**
 * Query Parser
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query;
use Arvici\Exception\QueryBuilderParseException;
use Arvici\Heart\Database\Query\Part\Column;
use Arvici\Heart\Database\Query\Part\Table;

/**
 * Class Parser
 *
 * @package Arvici\Heart\Database\Query
 */
class Parser
{
    /** @var bool $parsed Is the query parsed? */
    private $parsed = false;


    /** @var string $sql */
    private $sql;

    /** @var array $bind */
    private $bind = array();


    /**
     * @var Query
     */
    private $query;


    public function __construct(Query &$query)
    {
        $this->query = $query;
    }

    /**
     * Validate query.
     *
     * Throws exceptions when invalid
     *
     * @throws QueryBuilderParseException
     */
    public function validate()
    {
        // TODO: Add validations.
        return true;
    }

    /**
     * Get sql string
     *
     * @throws QueryBuilderParseException
     *
     * @return string
     */
    public function getSQL()
    {
        $this->parse();

        // TODO Parse into SQL
        return $this->sql;
    }


    /**
     * Get bind array in order!
     * Always uses numeric placements so the order is really important.
     * Tests should constantly test the order in several circumstances.
     *
     * @return array Returns array with numeric key and value of the key.
     */
    public function getBind()
    {
        $this->parse();

        // TODO Parse into bind array
        return $this->bind;
    }


    /**
     * Parse the Query object.
     *
     * @throws QueryBuilderParseException
     * @throws \Exception
     *
     */
    private function parse()
    {
        // Skip if already parsed!
        if ($this->parsed) {
            return;
        }

        // Type switch
        switch($this->query->mode){
            case Query::MODE_SELECT:
                $this->parseSelectQuery(); break;
            case Query::MODE_NONE:
                throw new QueryBuilderParseException("Query has no mode yet!"); break; // @codeCoverageIgnore
            default:
                throw new QueryBuilderParseException("Query mode is invalid!"); break; // @codeCoverageIgnore
        }


        // Set parsed to true.
        $this->parsed = true;
    }


    /**
     * Select Mode Parser.
     */
    private function parseSelectQuery()
    {
        // Parse select ?
        $selectSql = Column::generateQueryString($this->query->select);

        // Parse table
        $fromSql = Table::generateQueryString($this->query->table);

        $whereSql = null;
        $whereBind = array();


        $sql = [
            'select' => $selectSql,
            'from' => $fromSql,
            'where' => $whereSql
            // TODO: Add other parts.
        ];

        $this->bind = array_merge(
            $this->bind,
            $whereBind
        );

        $this->sql = $this->combineSelectQuery($sql);
    }

    /**
     * Combine select query.
     *
     * @param array $sqlParts
     *
     * @return string
     */
    private function combineSelectQuery($sqlParts)
    {
        // Add select columns and tables.
        $output = "SELECT " . $sqlParts['select'] . " FROM " . $sqlParts['from'];

        // Add where when defined.
        if ($sqlParts['where'] !== null) {
            // Add where
            $output .= " WHERE " . $sqlParts['where'];
        }

        // TODO: Add other parts.


        return $output;
    }
}