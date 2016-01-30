<?php
/**
 * Column.php
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
 * Column, used for selecting.
 *
 * @package Arvici\Heart\Database\Query\Part
 */
class Column implements Part
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $as;

    /**
     * Column constructor.
     *
     * @param string $name Name of column to select.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @codeCoverageIgnore
     */
    public function setColumnName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string|null $as
     */
    public function setColumnAs($as)
    {
        $this->as = $as;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getColumnAs()
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
        $query->select[] = $this;
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
            if ($part instanceof Column) {
                if ($part->getColumnName() === '*') {
                    $return .= "*";
                } else {
                    $return .= "`{$part->getColumnName()}`"; // @codeCoverageIgnore
                }

                if ($part->getColumnAs() !== null) {
                    $return .= " " . $part->getColumnAs(); // @codeCoverageIgnore
                }

                $return .= ",";
            }
        }

        return ($return !== "" ? substr($return, 0, -1) : "*");
    }
}