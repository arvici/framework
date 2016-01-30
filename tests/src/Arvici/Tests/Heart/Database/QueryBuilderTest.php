<?php
/**
 * QueryBuilder Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Database;

use App\TestUtils;
use Arvici\Heart\Database\Database;
use Arvici\Heart\Database\Query\QueryBuilder;

/**
 * QueryBuilder Test Case
 * @package Arvici\Tests\Heart\Database
 *
 * @covers \Arvici\Heart\Database\Query\Query
 * @covers \Arvici\Heart\Database\Query\QueryBuilder
 * @covers \Arvici\Heart\Database\Query\Part
 * @covers \Arvici\Heart\Database\Query\Part\Column
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Arvici\Heart\Database\Connection::build
     * @covers \Arvici\Heart\Database\Driver\PDOConnection::build
     * @covers \Arvici\Heart\Database\Database::driver
     * @covers \Arvici\Heart\Database\Connection::getDriver
     * @covers \Arvici\Heart\Database\Driver\PDOConnection::getDriver
     * @covers \Arvici\Heart\Database\Driver\MySQL\Connection::getDriver
     */
    public function testSelect()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();
        $query = $connection->build();

        $this->assertInstanceOf(QueryBuilder::class, $query);
    }
}
