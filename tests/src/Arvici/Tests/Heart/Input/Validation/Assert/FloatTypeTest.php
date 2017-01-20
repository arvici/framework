<?php
/**
 * FloatTypeTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;


use Arvici\Heart\Input\Validation\Assert\FloatType;

/**
 * Class FloatTypeTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\FloatType
 */
class FloatTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testFloat()
    {
        $float = new FloatType();

        $data = [
            '0.001',
            0.001,
            '0',
            null,
            '',
            false,
            1
        ];

        $this->assertTrue($float->execute($data, 1));
        $this->assertTrue($float->execute($data, 2));
        $this->assertFalse($float->execute($data, 3));
        $this->assertFalse($float->execute($data, 4));
        $this->assertFalse($float->execute($data, 5));
        $this->assertTrue($float->execute($data, 6));
        $this->assertFalse($float->execute($data, 7));
    }
}
