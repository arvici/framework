<?php
/**
 * QueryBuilder Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Database\Query;

use App\TestUtils;
use Arvici\Heart\Database\Database;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * QueryBuilder Test Case
 * @package Arvici\Tests\Heart\Database
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

    public function testInit()
    {
        TestUtils::clearDatabase();

        $conn = Database::connection();
        $query = $conn->build();

        $this->assertInstanceOf(QueryBuilder::class, $query);
    }
}
