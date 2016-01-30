<?php
/**
 * QueryBuilder Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Database\Query;

use App\TestUtils;
use Arvici\Heart\Database\Database;
use Arvici\Heart\Database\Query\Query;
use Arvici\Heart\Database\Query\QueryBuilder;

/**
 * QueryBuilder Test Case
 * @package Arvici\Tests\Heart\Database
 *
 * @covers \Arvici\Heart\Database\Query\Query
 * @covers \Arvici\Heart\Database\Query\QueryBuilder
 * @covers \Arvici\Heart\Database\Query\Part
 * @covers \Arvici\Heart\Database\Query\Part\Column
 *
 * @covers \Arvici\Heart\Database\Connection::build
 * @covers \Arvici\Heart\Database\Driver\PDOConnection::build
 * @covers \Arvici\Heart\Database\Database::driver
 * @covers \Arvici\Heart\Database\Connection::getDriver
 * @covers \Arvici\Heart\Database\Driver\PDOConnection::getDriver
 * @covers \Arvici\Heart\Database\Driver\MySQL\Connection::getDriver
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{


    public function testSelect()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();
        $query = $connection->build();

        $this->assertInstanceOf(QueryBuilder::class, $query);


        $query->select("*");


        $queryObject = $query->getRawQuery();
        $this->assertEquals(Query::MODE_SELECT, $queryObject->mode);
        $this->assertCount(1, $queryObject->select);


        $query->select(array("column" => "alias_1", "column2"), true);
        $query->select("column3");
        $query->select("column4, column5");

        $queryObject = $query->getRawQuery();
        $this->assertEquals(Query::MODE_SELECT, $queryObject->mode);
        $this->assertCount(5, $queryObject->select);
        $this->assertEquals("column", $queryObject->select[0]->getColumnName());
        $this->assertEquals("alias_1", $queryObject->select[0]->getColumnAs());

        $this->assertEquals("column2", $queryObject->select[1]->getColumnName());
        $this->assertNull($queryObject->select[1]->getColumnAs());

        $this->assertEquals("column3", $queryObject->select[2]->getColumnName());
        $this->assertNull($queryObject->select[2]->getColumnAs());

        $this->assertEquals("column4", $queryObject->select[3]->getColumnName());
        $this->assertNull($queryObject->select[3]->getColumnAs());

        $this->assertEquals("column5", $queryObject->select[4]->getColumnName());
        $this->assertNull($queryObject->select[4]->getColumnAs());

        // TODO: Add fetch test here too.
    }

    public function testSelectInvalid()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();
        $query = $connection->build();

        $query->select(false);
        // TODO: Add fetch so it will throw the exception on stack!

        // TODO: Test invalid mode
    }
}
