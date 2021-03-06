<?php
/**
 * Connection encapsulation for every pdo connection.
 * With the standard SQL syntax, will be override if it uses specific query language by the drivers connection.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Database\Driver;

use Arvici\Exception\DatabaseException;
use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Database;
use Arvici\Heart\Database\Driver;
use Arvici\Heart\Log\DoctrineLogBridge;
use Arvici\Heart\Tools\DebugBarHelper;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\LoggerChain;

/**
 * Connection encapsulation for every pdo connection.
 * With the standard SQL syntax, will be override if it uses specific query language by the drivers connection.
 *
 * @package Arvici\Heart\Database\Driver
 */
abstract class PDOConnection extends \PDO implements Connection
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
        $statement = $this->prepare($query);
        foreach ($bind as $key => $value) { // @codeCoverageIgnore
            $statement->bindValue($key, $value); // @codeCoverageIgnore
        }

        $returnType = Database::normalizeFetchType($returnType);

        if ($returnType === Database::FETCH_CLASS) {
            // Verify class
            new \ReflectionClass($returnClass);

            $statement->setFetchMode($returnType, $returnClass);
        } else {
            $statement->setFetchMode($returnType);
        }

        // Execute and fetch.
        $statement->execute();

        return $statement->fetchAll();
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
        // Prepare
        $columnPart = "";
        $valuePart = "";

        // Loop the data
        foreach ($data as $column => $value) {
            $columnPart .= "`$column`, ";
            $valuePart .= "?, ";
        }

        // Remove last commas
        $columnPart = substr($columnPart, 0, -2);
        $valuePart = substr($valuePart, 0, -2);

        $query = "INSERT INTO `$table` ($columnPart) VALUES ($valuePart)";


        // Prepare and bind
        $statement = $this->prepare($query);

        $idx = 0;
        foreach ($data as $key => $value) {
            $statement->bindValue(($idx+1), $value, Database::typeOfValue($value));
            $idx++;
        }

        // Execute and return the execute returning value.
        return $statement->execute();
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
        $query = "UPDATE `$table` SET ";

        $bind = array();

        // Add the sets
        foreach ($data as $column => $value) {
            $query .= "`$column` = ?, ";
            $bind[] = $value;
        }
        // Remove last ,
        $query = substr($query, 0, -2);


        $query .= " WHERE ";

        // Where
        foreach ($where as $column => $value) {
            $query .= "`$column` = ? AND ";
            $bind[] = $value;
        }

        // Remove last AND
        $query = substr($query, 0, -5);

        // Prepare, bind and execute.
        $statement = $this->prepare($query);
        foreach ($bind as $idx => $value) {
            $statement->bindValue(($idx + 1), $value, Database::typeOfValue($value));
        }

        return $statement->execute();
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
        $query = "DELETE FROM `$table` WHERE ";

        $bind = array();
        foreach ($where as $column => $value) {
            $query .= "`$column` = ? AND ";
            $bind[] = $value;
        }

        $query = substr($query, 0, -5);

        // Prepare, bind and execute
        $statement = $this->prepare($query);
        foreach ($bind as $idx => $value) {
            $statement->bindValue(($idx + 1), $value, Database::typeOfValue($value));
        }

        return $statement->execute();
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
        $this->raw("TRUNCATE TABLE `$table`;");
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
        return $this->lastInsertId($name);
    }

    /**
     * Execute query. Warning! No escaping!
     *
     * @param string $query
     * @param bool $return Return something back? (Fetch)
     * @param int $fetchMode Fetch mode when returning something.
     * @param string $fetchClass Class to fetch
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function raw($query, $return = false, $fetchMode = null, $fetchClass = null)
    {
        if (! $return) {
            return $this->exec($query);
        }

        $fetchMode = Database::normalizeFetchType($fetchMode);

        $statement = $this->query($query);

        if ($fetchMode === Database::FETCH_CLASS) {
            $statement->setFetchMode($fetchMode, $fetchClass);
        } else {
            $statement->setFetchMode($fetchMode);
        }

        return $statement->fetchAll();
    }

    /**
     * Get a new Query Builder instance for this connection.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function build()
    {
        $dbalConnection = $this->getDbalConnection();

        if ($dbalConnection) {
            return $dbalConnection->createQueryBuilder();
        }
        return null; // @codeCoverageIgnore
    }

    /**
     * Get DBAL Connection
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getDbalConnection()
    {
        $dbalDriver = '';
        $driverCode = $this->getDriver()->getCode();

        if ($driverCode === 'MySQL') $dbalDriver = 'pdo_mysql';
        if ($dbalDriver == '') return null;

        $config = new Configuration();
        $loggerChain = new LoggerChain();

        if (\Arvici\Heart\Config\Configuration::get('app.env') == 'development') {
            $loggerChain->addLogger(new DoctrineLogBridge(\Logger::getInstance()->getMonologInstance()));
            $loggerChain->addLogger(DebugBarHelper::getInstance()->getDebugStack());
        }
        $config->setSQLLogger($loggerChain);

        return DriverManager::getConnection([
            'driver' => $dbalDriver,
            'pdo' => $this
        ], $config);
    }

    /**
     * Get driver instance for this connection.
     *
     * @return Driver
     */
    abstract public function getDriver();
}
