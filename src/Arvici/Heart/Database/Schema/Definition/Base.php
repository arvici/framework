<?php
/**
 * Base Schema Definition
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Database\Schema\Definition;


/**
 * Base Schema Definition Class. Don't use it directly!
 * @package Arvici\Heart\Database\Schema\Definition
 * @api
 */
abstract class Base
{
    /**
     * The definition or well-known name.
     * @var string
     */
    protected $name;

    /**
     * The name that the driver uses, and the table/database/column is going to be named to.
     * Leave empty to be auto-generated.
     *
     * @var string
     */
    protected $dbName;


    public function __construct()
    {}

    /**
     * Set the name of the object.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the internal used name. This is mostly the name of the database object itself.
     *
     * @param string $dbName
     * @return $this
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
        return $this;
    }

    /**
     * Set the managed state of the object. Set to false to disable migrations on this object.
     *
     * @param boolean $managed
     * @return $this
     */
    public function setManaged($managed)
    {
        $this->managed = $managed;
        return $this;
    }
}
