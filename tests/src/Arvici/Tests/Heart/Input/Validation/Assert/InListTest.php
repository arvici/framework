<?php
/**
 * InListTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\InList;

/**
 * Class InListTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\InList
 */
class InListTest extends \PHPUnit_Framework_TestCase
{
    public function testInList()
    {
        $contain = new InList(['one', '2'], false);

        $data = array('col' => 'one', 'col2' => 2, 'col3' => 'no');

        $this->assertTrue($contain->execute($data, 'col'));
        $this->assertTrue($contain->execute($data, 'col2'));
        $this->assertFalse($contain->execute($data, 'col3'));
        $this->assertFalse($contain->execute($data, 'col4'));


        $contain = new InList([1, 2, 3], true);

        $data = array(1, '2', 3.00, 'multi');

        $this->assertTrue($contain->execute($data, 0));
        $this->assertFalse($contain->execute($data, 1));
        $this->assertFalse($contain->execute($data, 2));
        $this->assertFalse($contain->execute($data, 3));
    }
}
