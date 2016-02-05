<?php
/**
 * RequiredTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;
use Arvici\Heart\Input\Validation\Assert\Required;


/**
 * Class RequiredTest
 * @package Arvici\Tests\Heart\Validation\Input\Assert
 *
 * @covers \Arvici\Heart\Input\Validation\Assert\Required
 */
class RequiredTest extends \PHPUnit_Framework_TestCase
{
    public function testRequired()
    {
        $required = new Required();

        $data = [
            'null' => null,
            'false' => false
        ];

        $this->assertEquals("Required", $required->assertName());
        $this->assertFalse($required->execute($data, 'null'));
        $this->assertFalse($required->execute($data, 'nonexisting'));
        $this->assertTrue($required->execute($data,  'false'));
    }
}
