<?php
/**
 * Cache Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Config;

use Arvici\Exception\NotFoundException;
use Arvici\Heart\Cache\Cache;
use Stash\Interfaces\ItemInterface;


/**
 * Configuration Test Case
 *
 * @package Arvici\Tests\Heart\Config
 *
 * @coversDefaultClass \Arvici\Heart\Cache\Cache
 * @covers \Arvici\Heart\Cache\Cache
 * @covers \Arvici\Heart\Cache\Pool
 * @covers \Arvici\Heart\Config\Configuration
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testDriverCreation ()
    {
        $cacheManager = Cache::getInstance();

        $this->assertNotNull($cacheManager);
        $this->assertInstanceOf(Cache::class, $cacheManager);
    }

    public function testInvalidPool ()
    {
        try {
            Cache::getInstance()->getPool('non-existing-pool');
            $this->assertTrue(false);
        } catch (NotFoundException $nfe) {
            $this->assertTrue(true);
        }
    }

    public function testWriting ()
    {
        $cache = Cache::getInstance()->getPool();
        $cache->clear();


        $item = $cache->getItem('test-1');
        $this->assertInstanceOf(ItemInterface::class, $item);
        $this->assertTrue($item->isMiss());

        $item->set(true)->save();

        //

        $item = $cache->getItem('test-1');
        $this->assertInstanceOf(ItemInterface::class, $item);

        $this->assertFalse($item->isMiss());
        $this->assertTrue($item->get());
    }
}
