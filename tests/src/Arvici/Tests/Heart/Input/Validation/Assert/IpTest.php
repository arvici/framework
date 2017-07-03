<?php
/**
 * IpTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Ip;
use PHPUnit\Framework\TestCase;

/**
 * Class IpTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Ip
 */
class IpTest extends TestCase
{
    public function testIp()
    {
        $ip = new Ip();

        $data = [
            '1.2.3.4', // valid
            '1.2.3', // invalid
            '0.0.0.0', // valid

            '1200:0000:AB00:1234:0000:2552:7777:1313', // valid
            '21DA:D3:0:2F3B:2AA:FF:FE28:9C5A', // valid
            '1200::AB00:1234::2552:7777:1313', // Invalid
        ];

        $this->assertTrue($ip->execute($data, 0));
        $this->assertFalse($ip->execute($data, 1));
        $this->assertTrue($ip->execute($data, 2));
        $this->assertTrue($ip->execute($data, 3));
        $this->assertTrue($ip->execute($data, 4));
        $this->assertFalse($ip->execute($data, 5));
    }
}
