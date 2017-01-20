<?php
/**
 * Configuration Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Config;
use Arvici\Heart\Config\Configuration;

/**
 * Configuration Test Case
 *
 * @package Arvici\Tests\Heart\Config
 *
 * @coversDefaultClass \Arvici\Heart\Config\Configuration
 * @covers \Arvici\Heart\Config\Configuration
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testDefineConfiguration()
    {
        Configuration::define('test', function() {
            return [
                'testkey' => true,
                'testarray' => array(true),
                'test1' => 1
            ];
        });

        $this->assertEquals(true, Configuration::get('test.testkey'));
        $this->assertEquals(array(true), Configuration::get('test.testarray'));
        $this->assertEquals(1, Configuration::get('test.test1'));
    }

    public function testSet()
    {
        Configuration::set('test.test', false);
        Configuration::set('test.test2', true);

        $this->assertFalse(Configuration::get('test.test'));
        $this->assertTrue(Configuration::get('test.test2'));

        $this->assertArraySubset([
            'test' => false,
            'test2' => true
        ], Configuration::get('test.*'));

    }

    public function testGetDefaultSection()
    {
        $this->assertTrue(Configuration::get('cache.enabled'));
        $this->assertNotNull(Configuration::get('test.*'));
    }

    public function testInvalidNames()
    {
        $this->assertFalse(Configuration::get('test_test', false));
        $this->assertFalse(Configuration::set('test_test', true));
    }

    public function testInvalidDefine()
    {
        try {
            Configuration::define('testinvalid', function() {
                return null;
            });

            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
