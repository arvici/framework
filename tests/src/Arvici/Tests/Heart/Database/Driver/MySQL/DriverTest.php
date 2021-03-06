<?php
/**
 * DriverTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Database\Drivers\MySQL;

use App\TestUtils;
use Arvici\Heart\Database\Database;
use PHPUnit\Framework\TestCase;

/**
 * MySQL Driver Test Case
 *
 * @package Arvici\Tests\Heart\Database\Driver\MySQL
 *
 * @covers \Arvici\Heart\Database\Driver\MySQL\Driver
 */
class DriverTest extends TestCase
{
    public function testDriver()
    {
        TestUtils::clearDatabase();

        $driver = Database::driver();

        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Driver\\MySQL\\Driver", $driver);
        $this->assertInstanceOf("\\Arvici\\Heart\\Database\\Driver", $driver);
    }
}
