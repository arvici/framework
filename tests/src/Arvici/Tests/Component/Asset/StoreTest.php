<?php
/**
 * StoreTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Component\Asset;

use Arvici\Component\Asset\Store;
use PHPUnit\Framework\TestCase;

/**
 * Class StoreTest
 * @package Arvici\Tests\Component\Asset
 *
 * @covers \Arvici\Component\Asset\Store
 */
class StoreTest extends TestCase
{
    public function testTemplateStore()
    {
        $store = Store::template();

        $this->assertInstanceOf(Store::class, $store);

        $this->assertEquals('http://localhost:8080/assets/img/pizza.png', $store->getUrl('img/pizza.png'));
        $this->assertEquals('http://www.example.com/pizza.png', $store->getUrl('http://www.example.com/pizza.png'));

        $storePath = $store->getPath('img/pizza.png');
        $realPath = APPPATH . 'Assets'.DS.'img'.DS.'pizza.png';

        $storePath = str_replace('\\', '/', $storePath);
        $realPath = str_replace('\\', '/', $realPath);
        $this->assertEquals($realPath, $storePath);
    }
}
