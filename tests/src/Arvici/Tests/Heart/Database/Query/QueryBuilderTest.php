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
use Arvici\Heart\Database\Query\ExpressionBuilder;
use Arvici\Heart\Database\Query\Query;
use Arvici\Heart\Database\Query\QueryBuilder;

/**
 * QueryBuilder Test Case
 * @package Arvici\Tests\Heart\Database
 *
 * @covers \Arvici\Heart\Database\Query\Query
 * @covers \Arvici\Heart\Database\Query\QueryBuilder
 *
 * @covers \Arvici\Heart\Database\Query\Part
 * @covers \Arvici\Heart\Database\Query\Part\Column
 * @covers \Arvici\Heart\Database\Query\Part\Table
 *
 * @covers \Arvici\Heart\Database\Query\Parser
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


        // === From (table selection)
        $query->from("table1");

        $this->assertCount(1, $queryObject->table);

        $query->from("table2, table3");
        $this->assertCount(3, $queryObject->table);
        $this->assertEquals("table1", $queryObject->table[0]->getTableName());
        $this->assertEquals("table2", $queryObject->table[1]->getTableName());
        $this->assertEquals("table3", $queryObject->table[2]->getTableName());
        $this->assertNull($queryObject->table[2]->getTableAs());

        $query->from(array("table4" => "awesometable"));
        $this->assertCount(4, $queryObject->table);
        $this->assertEquals("table4", $queryObject->table[3]->getTableName());
        $this->assertEquals("awesometable", $queryObject->table[3]->getTableAs());

        // Replace
        $query->from("table", true);
        $this->assertCount(1, $queryObject->table);
    }



    public function testSelectFetch()
    {
        TestUtils::clearDatabase();
        TestUtils::resetDatabase();

        $connection = Database::connection();

        $qb = $connection->build();
        $all = $qb->select("*")->from("posts")->fetchAll();

        $this->assertCount(7, $all);

        $singleObject = $qb->fetchSingle(Database::FETCH_OBJECT);
        $this->assertTrue(is_object($singleObject));
    }




    public function testSelectInvalid()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();
        $query = $connection->build();

        $query->select(false);
        // TODO: Add fetch so it will throw the exception on stack!

        $query->from(false);

        // TODO: Test invalid mode
    }
}
