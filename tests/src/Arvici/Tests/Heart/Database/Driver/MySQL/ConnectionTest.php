<?php
/**
 * ConnectionTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Database\Drivers\MySQL;

use App\Model\FetchTestPosts;
use App\TestUtils;
use Arvici\Heart\Database\Database;
use Doctrine\DBAL\Driver\Connection;
use PHPUnit\Framework\TestCase;

/**
 * MySQL Connection Test Case
 *
 * @package Arvici\Tests\Heart\Database\Driver\MySQL
 *
 * @covers \Arvici\Heart\Database\Driver\MySQL\Connection
 * @covers \Arvici\Heart\Database\Driver\PDOConnection
 * @covers \Arvici\Heart\Database\Database
 */
class ConnectionTest extends TestCase
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

        $this->assertInstanceOf(Connection::class, $connection->getDbalConnection());
    }

    public function testSelect()
    {
        $connection = Database::connection();

        $all = $connection->select("SELECT * FROM posts");

        $this->assertCount(7, $all);
        $this->assertEquals("First Post", $all[0]['title']);
    }

    public function testUpdate()
    {
        $connection = Database::connection();

        $return = $connection->update('posts', ['title' => 'Mister Post :)', 'author' => 2, 'active' => 0], ['title' => 'Mister Post', 'author' => 1]);

        $this->assertTrue($return);

        // Select it
        $all = $connection->select("SELECT * FROM posts WHERE active = 0;");

        $this->assertCount(1, $all);
        $this->assertEquals('Mister Post :)', $all[0]['title']);
        $this->assertEquals(2, $all[0]['author']);
    }

    public function testInsert()
    {
        $connection = Database::connection();

        $return = $connection->insert('posts', ['title' => 'sample', 'author' => 2, 'active' => 1, 'publishdate' => date('Y-m-d'), 'Content' => '']);
        $this->assertTrue($return);

        $id = $connection->lastInsertedId();
        $this->assertEquals(8, $id);
    }

    public function testDelete()
    {
        $connection = Database::connection();

        $return = $connection->insert('posts', ['title' => 'sample', 'author' => 2, 'active' => 1, 'publishdate' => date('Y-m-d'), 'Content' => '']);
        $this->assertTrue($return);

        $id = $connection->lastInsertedId();

        // Remove
        $result = $connection->delete('posts', ['id' => $id]);
        $this->assertTrue($result);
    }

    public function testRawFetch()
    {
        $connection = Database::connection();

        $all = $connection->raw("SELECT * FROM posts", true, Database::FETCH_OBJECT);
        $this->assertCount(7, $all);

        $this->assertObjectHasAttribute('id', $all[0]);
    }

    public function testFetchClass()
    {
        $connection = Database::connection();

        $all = $connection->select("SELECT * FROM posts", array(), Database::FETCH_CLASS, FetchTestPosts::class);

        foreach ($all as $post)
        {
            $this->assertInstanceOf(FetchTestPosts::class, $post);
            $this->assertNotNull($post->id);
        }

        $all = $connection->raw("SELECT * FROM posts", true, Database::FETCH_CLASS, FetchTestPosts::class);

        foreach ($all as $post)
        {
            $this->assertInstanceOf(FetchTestPosts::class, $post);
            $this->assertNotNull($post->id);
        }
    }
}
