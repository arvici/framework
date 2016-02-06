<?php
/**
 * IntegerTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\IntegerType;

/**
 * Class IntegerTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\IntegerType
 */
class IntegerTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInteger()
    {
        $integer = new IntegerType();

        $data = [
            -1,
            1,
            0,
            null,
            'invalid',
            0.1,
            0.00001,
            false,
            true,
            '1'
        ];

        $this->assertTrue($integer->execute($data, 0));
        $this->assertTrue($integer->execute($data, 1));
        $this->assertTrue($integer->execute($data, 2));
        $this->assertFalse($integer->execute($data, 3));
        $this->assertFalse($integer->execute($data, 4));
        $this->assertFalse($integer->execute($data, 5));
        $this->assertFalse($integer->execute($data, 6));
        $this->assertFalse($integer->execute($data, 7));
        $this->assertTrue($integer->execute($data, 8));
        $this->assertTrue($integer->execute($data, 9));
    }
}
