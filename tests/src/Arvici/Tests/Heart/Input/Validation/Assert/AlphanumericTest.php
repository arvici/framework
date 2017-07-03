<?php
/**
 * AlphanumericTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Alphanumeric;
use PHPUnit\Framework\TestCase;

/**
 * Alphanumeric Test Class
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Alphanumeric
 * @covers \Arvici\Heart\Input\Validation\Assert\Regex
 */
class AlphanumericTest extends TestCase
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


        // Test dash+underscore
        $alpha = new Alphanumeric(true, true);

        $data = [
            '1_valid',
            '1valid',
            '1-valid',
            '1 invalid'
        ];

        $this->assertTrue($alpha->execute($data, 0));
        $this->assertTrue($alpha->execute($data, 1));
        $this->assertTrue($alpha->execute($data, 2));
        $this->assertFalse($alpha->execute($data, 3));
    }
}
