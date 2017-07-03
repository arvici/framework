<?php
/**
 * LengthTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Length;
use PHPUnit\Framework\TestCase;

/**
 * Length Test
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 *
 * @covers \Arvici\Heart\Input\Validation\Assert\Length
 */
class LengthTest extends TestCase
{
    public function testLength()
    {
        $data = [
            0,
            4,
            5,
            6
        ];

        $length = new Length(5);

        $this->assertEquals("Length", $length->assertName());

        $this->assertFalse($length->execute($data, 0));
        $this->assertFalse($length->execute($data, 1));
        $this->assertTrue ($length->execute($data, 2));
        $this->assertTrue ($length->execute($data, 3));



        // Between
        $data = [
            4,
            5,
            7,
            8
        ];

        $length = new Length(['min' => 5, 'max' => 7]);

        $this->assertEquals("Length", $length->assertName());

        $this->assertFalse($length->execute($data, 0));
        $this->assertTrue ($length->execute($data, 1));
        $this->assertTrue ($length->execute($data, 2));
        $this->assertFalse($length->execute($data, 3));



        // Max only
        $data = [
            2,
            3,
            4,
            5
        ];

        $length = new Length(['max' => 3]);

        $this->assertEquals("Length", $length->assertName());

        $this->assertTrue ($length->execute($data, 0));
        $this->assertTrue ($length->execute($data, 1));
        $this->assertFalse($length->execute($data, 2));
        $this->assertFalse($length->execute($data, 3));

        // Invalid
        $length = new Length();

        $this->assertFalse($length->execute($data, 1));
    }
}
