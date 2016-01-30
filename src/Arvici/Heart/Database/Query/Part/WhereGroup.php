<?php
/**
 * Where Group Part
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query\Part;

use Arvici\Exception\QueryException;
use Arvici\Heart\Database\Query\Part;
use Arvici\Heart\Database\Query\Query;
use Arvici\Heart\Database\Statement;

/**
 * Where condition.
 *
 * @package Arvici\Heart\Database\Query\Part
 */
class WhereGroup implements Part
{
    const TYPE_AND = "AND";
    const TYPE_OR  = "OR" ;

    /**
     * @var array $where Array with more groups or where parts.
     */
    private $where = array();

    private $type;

    /**
     * Where constructor.
     *
     * @param string $type AND or OR.
     */
    public function __construct($type = "AND")
    {
        $this->where = array();
        $this->type = $type;
    }

    /**
     * Add all where entries.
     *
     * @param Where[]|WhereGroup[]|array $entries
     */
    public function addAll($entries)
    {
        foreach ($entries as $entry)
        {
            $this->add($entry);
        }
    }


    public function add($entry)
    {
        if ($entry instanceof Where) {
            $this->where[] = $entry;
        }
        if ($entry instanceof WhereGroup) {
            $this->where[] = $entry;
        }
    }







    /**
     * Get part name. Used for building and combining together.
     *
     * @return mixed
     */
    public function getPartName()
    {
        return self::class;
    }

    /**
     * Append to query.
     *
     * @param $query
     *
     * @return bool
     *
     * @throws QueryException
     */
    public function appendQuery(Query &$query)
    {
        return false;
    }

    /**
     * Generate Query String for the parts given.
     *
     * @param Part[] $parts
     *
     * @return string
     *
     * @throws QueryException
     */
    public static function generateQueryString(array $parts)
    {
        $return = "";

        foreach ($parts as $part) {
            if ($part instanceof Table) {
                $return .= "`{$part->getTableName()}`";
                if ($part->getTableAs() !== null) {
                    $return .= " " . $part->getTableAs();
                }

                $return .= ",";
            }
        }

        return ($return !== "" ? substr($return, 0, -1) : "*");
    }
}