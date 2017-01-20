<?php
/**
 * NotTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Contain;
use Arvici\Heart\Input\Validation\Assert\Length;
use Arvici\Heart\Input\Validation\Assert\Not;

/**
 * Class NotTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Not
 * @covers \Arvici\Heart\Input\Validation\Assert\Length
 * @covers \Arvici\Heart\Input\Validation\Assert\Contain
 */
class NotTest extends \PHPUnit_Framework_TestCase
{
    public function testNot()
    {
        $not = new Not([
            new Length(4),
            new Contain(['not']),
            false // Wrong one, should skip this one.
        ]);

        $data = [
            't',     // Should pass
            'test',  // should NOT pass
            'tes',   // should pass
            'not',   // should NOT pass
            'notnot'// should NOT pass
        ];

        $this->assertTrue($not->execute($data, 0));
        $this->assertFalse($not->execute($data, 1));
        $this->assertTrue($not->execute($data, 2));
        $this->assertFalse($not->execute($data, 3));
        $this->assertFalse($not->execute($data, 4));

    }
}
