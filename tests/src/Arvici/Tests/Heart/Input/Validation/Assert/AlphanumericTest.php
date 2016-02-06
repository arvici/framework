<?php
/**
 * AlphanumericTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Alphanumeric;

/**
 * Class ContainTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Alphanumeric
 */
class AlphanumericTest extends \PHPUnit_Framework_TestCase
{
    public function testAlphanumeric()
    {
        $alpha = new Alphanumeric();

        $data = [
            '1sValid',
            '1s_InValid',
            '1s InValid',
            '$invalid',
            ''
        ];

        $this->assertTrue($alpha->execute($data, 0));
        $this->assertFalse($alpha->execute($data, 1));
        $this->assertFalse($alpha->execute($data, 2));
        $this->assertFalse($alpha->execute($data, 3));
        $this->assertFalse($alpha->execute($data, 4));
    }
}
