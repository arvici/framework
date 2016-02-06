<?php
/**
 * AlphaTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Alpha;

/**
 * Class AlphaTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Alpha
 * @covers \Arvici\Heart\Input\Validation\Assert\Regex
 */
class AlphaTest extends \PHPUnit_Framework_TestCase
{
    public function testAlphanumeric()
    {
        $alpha = new Alpha();

        $data = [
            '1invalid',
            'valid',
            'VaLiD',
            'invalid ',
            'inva_lid',
            ''
        ];

        $this->assertFalse($alpha->execute($data, 0));
        $this->assertTrue($alpha->execute($data, 1));
        $this->assertTrue($alpha->execute($data, 2));
        $this->assertFalse($alpha->execute($data, 3));
        $this->assertFalse($alpha->execute($data, 4));


        // Test dash + underscore
        $alpha = new Alpha(true, true);

        $data = [
            'inval#d_',
            'val_id',
            'vali-d',
            'inval id'
        ];

        $this->assertFalse($alpha->execute($data, 0));
        $this->assertTrue($alpha->execute($data, 1));
        $this->assertTrue($alpha->execute($data, 2));
        $this->assertFalse($alpha->execute($data, 3));
    }
}
