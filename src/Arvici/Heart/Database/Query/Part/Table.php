<?php
/**
 * Table Part
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
 * Table, used for any mode.
 *
 * @package Arvici\Heart\Database\Query\Part
 */
class Table implements Part
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $as;

    /**
     * Table constructor.
     *
     * @param string $name Name of table to select/update/delete or anything else.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @codeCoverageIgnore
     */
    public function setTableName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string|null $as
     */
    public function setTableAs($as)
    {
        $this->as = $as;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getTableAs()
    {
        return $this->as;
    }







    /**
     * Get part name. Used for building and combining together.
     * @codeCoverageIgnore
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
        $query->table[] = $this;
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