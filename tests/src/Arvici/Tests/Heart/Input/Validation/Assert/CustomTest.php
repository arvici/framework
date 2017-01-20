<?php
/**
 * CustomTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;


use Arvici\Heart\Input\Validation\Assert\Custom;

/**
 * Class CustomTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 *
 * @covers \Arvici\Heart\Input\Validation\Assert\Custom
 */
class CustomTest extends \PHPUnit_Framework_TestCase
{
    public function testCustom()
    {
        $called = false;

        $custom = new Custom(function ($value, $options) use (&$called) {
            $called = true;

            return $value === 1;
        });


        $data = [
            '',
            '1',
            1
        ];

        $this->assertFalse($custom->execute($data, 0));
        $this->assertFalse($custom->execute($data, 1));
        $this->assertTrue($custom->execute($data, 2));

        $this->assertTrue($called);
    }
}
