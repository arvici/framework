<?php
/**
 * BooleanTypeTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\BooleanType;

/**
 * Class BooleanTypeTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\BooleanType
 */
class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBoolean()
    {
        $boolean = new BooleanType();

        $data = [
            1,
            0,
            null,
            true,
            false
        ];

        $this->assertFalse($boolean->execute($data, 0));
        $this->assertFalse($boolean->execute($data, 1));
        $this->assertFalse($boolean->execute($data, 2));
        $this->assertTrue($boolean->execute($data, 3));
        $this->assertTrue($boolean->execute($data, 4));
    }
}
