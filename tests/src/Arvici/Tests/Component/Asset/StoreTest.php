<?php
/**
 * StoreTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Component\Asset;

use Arvici\Component\Asset\Store;

/**
 * Class StoreTest
 * @package Arvici\Tests\Component\Asset
 *
 * @covers \Arvici\Component\Asset\Store
 */
class StoreTest extends \PHPUnit_Framework_TestCase
{
    public function testTemplateStore()
    {
        $store = Store::template();

        $this->assertInstanceOf(Store::class, $store);

        $this->assertEquals('http://localhost:8080/assets/img/pizza.png', $store->getUrl('img/pizza.png'));
        $this->assertEquals('http://www.example.com/pizza.png', $store->getUrl('http://www.example.com/pizza.png'));
        $this->assertEquals(APPPATH . 'Template'.DS.'Default'.DS.'img'.DS.'pizza.png', $store->getPath('img/pizza.png'));
    }
}
