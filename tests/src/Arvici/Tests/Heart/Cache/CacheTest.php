<?php
/**
 * Cache Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Config;

use Arvici\Heart\Cache\Cache;


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
}
