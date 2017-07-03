<?php
/**
 * DatabaseTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Database;


use App\TestUtils;
use Arvici\Exception\ConfigurationException;
use Arvici\Exception\DatabaseDriverException;
use Arvici\Heart\Database\Database;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseTest
 *
 * @package Arvici\Tests\Heart\Database
 *
 * @covers \Arvici\Heart\Database\Driver\MySQL\Driver
 * @covers \Arvici\Heart\Database\Driver\MySQL\Connection
 * @covers \Arvici\Heart\Database\Driver\PDOConnection
 * @covers \Arvici\Heart\Database\Database
 */
class DatabaseTest extends TestCase
{

    public function testDriverInit()
    {
        TestUtils::clearDatabase();

        $connection = Database::connection();

        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Driver\\MySQL\\Connection", $connection);
        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Connection", $connection);
        $this->assertInstanceOf("\\PDO", $connection);

        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Driver\\MySQL\\Driver", Database::driver());
    }

    public function testInvalidDriverInit()
    {
        // Non existing connection name
        TestUtils::clearDatabase();

        try {
            Database::connection('nonexisting');
            $this->assertTrue(false);
        } catch (ConfigurationException $ce) {
            $this->assertTrue(true);
        }




        // No valid configuration, incomplete!
        TestUtils::clearDatabase();

        try {
            Database::connection('incomplete');
            $this->assertTrue(false);
        } catch (DatabaseDriverException $dde) {
            $this->assertTrue(true);
        }


        // No driver defined.
        TestUtils::clearDatabase();

        try {
            Database::connection('nodriver');
            $this->assertTrue(false);
        } catch (ConfigurationException $ce) {
            $this->assertTrue(true);
        }

    }

    public function testTypeDetection()
    {
        $null = null;
        $int = 0;
        $bool = true;
        $string = 'string';

        $this->assertEquals(Database::TYPE_NULL, Database::typeOfValue($null));
        $this->assertEquals(Database::TYPE_INT, Database::typeOfValue($int));
        $this->assertEquals(Database::TYPE_BOOL, Database::typeOfValue($bool));
        $this->assertEquals(Database::TYPE_STR, Database::typeOfValue($string));
    }
}
