<?php
/**
 * ContainTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Contain;

/**
 * Class ContainTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Contain
 */
class ContainTest extends \PHPUnit_Framework_TestCase
{
    public function testContain()
    {
        $contain = new Contain("single", true);

        $data = array('col' => 'singlerecord', 'col2' => 'Singlerecord');

        $this->assertTrue($contain->execute($data, 'col'));
        $this->assertFalse($contain->execute($data, 'col2'));
        $this->assertFalse($contain->execute($data, 'col3'));


        $contain = new Contain(['single', 'multi'], false);

        $data = array('SingleOrMulti', 'S1ngle', 'single', 'multi');

        $this->assertTrue($contain->execute($data, 0));
        $this->assertFalse($contain->execute($data, 1));
        $this->assertTrue($contain->execute($data, 2));
        $this->assertTrue($contain->execute($data, 3));
    }
}
