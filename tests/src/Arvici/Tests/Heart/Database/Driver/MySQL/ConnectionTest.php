<?php
/**
 * ConnectionTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Database\Drivers\MySQL;

use App\TestUtils;
use Arvici\Heart\Database\Database;

/**
 * MySQL Connection Test Case
 *
 * @package Arvici\Tests\Heart\Database\Driver\MySQL
 *
 * @covers \Arvici\Heart\Database\Driver\MySQL\Connection
 * @covers \Arvici\Heart\Database\Driver\PDOConnection
 * @covers \Arvici\Heart\Database\Connection
 * @covers \Arvici\Heart\Database\Statement
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        TestUtils::resetDatabase('default');
        TestUtils::clearDatabase();
    }

    public function testConnect()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();

        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Driver\\MySQL\\Connection", $connection);
        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Connection", $connection);
        $this->assertInstanceOf("\\PDO", $connection);
    }

    public function testSelect()
    {
        $connection = Database::connection();

        $all = $connection->select("SELECT * FROM posts");

        $this->assertCount(7, $all);
        $this->assertEquals("First Post", $all[0]['title']);
    }
}
