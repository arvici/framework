<?php
/**
 * DateTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;


use Arvici\Heart\Input\Validation\Assert\Date;

/**
 * Class DateTest
 *
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 *
 * @covers \Arvici\Heart\Input\Validation\Assert\Date
 */
class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testDate()
    {
        $date = new Date();

        $data = [
            '11-11-2015',
            '11-99-2015',
            '11-11-1111',
            '0000000000',
            false,
            null,
            '12/25/2015',
            -1454759312
        ];

        $this->assertTrue($date->execute($data, 0));
        $this->assertFalse($date->execute($data, 1));
        $this->assertTrue($date->execute($data, 2));
        $this->assertFalse($date->execute($data, 3));
        $this->assertFalse($date->execute($data, 4));
        $this->assertFalse($date->execute($data, 5));
        $this->assertTrue($date->execute($data, 6));
        $this->assertFalse($date->execute($data, 7));


        // Format testing
        $date = new Date('d-m-Y H:i:s');

        $data = [
            '11-11-2015 12:00:01',
            '31-12-2015 23:59:59',
            '32-12-2015 23:00:00',
            '01-01-2200 00:00:00',
            '',
            false,
            '01/01/2015 00:00:00'
        ];

        $this->assertTrue($date->execute($data, 0));
        $this->assertTrue($date->execute($data, 1));
        $this->assertFalse($date->execute($data, 2));
        $this->assertTrue($date->execute($data, 3));
        $this->assertFalse($date->execute($data, 4));
        $this->assertFalse($date->execute($data, 5));
        $this->assertFalse($date->execute($data, 6));
    }
}
