<?php
/**
 * Data Collection Test
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Collections;

use Arvici\Heart\Collections\DataCollection;

/**
 * Data Collection Test
 * @package Arvici\Tests\Heart\Collections
 *
 * @coversDefaultClass \Arvici\Heart\Collections\DataCollection
 * @covers \Arvici\Heart\Collections\DataCollection
 */
class DataCollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     * @covers ::all
     */
    public function testBasicCollectionCreation()
    {
        $raw = array('one', 'two', 'three');
        $collection = new DataCollection($raw);
        $this->assertEquals($raw, $collection->all());
    }

    /**
     * @covers ::__construct
     * @covers ::set
     * @covers ::get
     * @covers ::all
     */
    public function testSetAndGet()
    {
        $collection = new DataCollection();

        $collection->set('test', true);

        $this->assertEquals(array('test' => true), $collection->all());
        $this->assertEquals(false, $collection->get('TEST', false));
        $this->assertEquals(false, $collection->get('test_nonext', false));
        $this->assertTrue($collection->get('test'));
    }

    /**
     * @covers ::__construct
     * @covers ::set
     * @covers ::all
     * @covers ::keys
     * @covers ::values
     */
    public function testSetAndKeysValues()
    {
        $collection = new DataCollection();

        $expect = array('test1' => true, 'test2' => 2);

        $collection->set('test1', true);
        $collection->set('test2', 2);

        $this->assertEquals($expect, $collection->all());
        $this->assertEquals(array_keys($expect), $collection->keys());
        $this->assertEquals(array_values($expect), $collection->values());

        $this->assertTrue($collection->exists('test1'));
        $this->assertTrue($collection->exists('test2'));
        $this->assertFalse($collection->exists('testtest'));

        $collection->remove('test1');
        $this->assertFalse($collection->exists('test1'));
    }

    /**
     * @covers ::__construct
     * @covers ::set
     * @covers ::all
     * @covers ::replace
     */
    public function testReplace()
    {
        $collection = new DataCollection();

        $expect = array('test1' => true, 'test2' => 2);

        $collection->set('test1', true);
        $collection->set('test2', 2);

        $this->assertEquals($expect, $collection->all());

        $expect = array('test1' => false, 'test2' => 1);
        $collection->replace(array('test1' => false, 'test2' => 1));

        $this->assertEquals($expect, $collection->all());
    }

    /**
     * @covers ::__construct
     * @covers ::all
     * @covers ::merge
     */
    public function testMerge()
    {
        $collection = new DataCollection(array('test' => true));

        $expect = array('test' => false, 'yes' => true);
        $collection->merge($expect);

        $this->assertEquals($expect, $collection->all());
    }

    /**
     * @covers ::__construct
     * @covers ::set
     * @covers ::isEmpty
     * @covers ::clear
     */
    public function testEmpty()
    {
        $collection = new DataCollection();
        $this->assertTrue($collection->isEmpty());

        $collection->set('test', true);

        $this->assertFalse($collection->isEmpty());

        $collection->clear();

        $this->assertTrue($collection->isEmpty());
    }


    /**
     * @covers ::__construct
     * @covers ::set
     * @covers ::count
     * @covers ::clear
     */
    public function testCount()
    {
        $collection = new DataCollection();
        $this->assertEquals(0, $collection->count());
        $this->assertCount(0, $collection);

        $collection->set('test1', true);
        $collection->set('test2', true);
        $collection->set('test3', null);

        $this->assertEquals(3, $collection->count());
        $this->assertCount(3, $collection);
    }

    /**
     * @covers ::__construct
     * @covers ::append
     */
    public function testAppend()
    {
        $collection = new DataCollection();

        $collection->append('first');
        $collection->append('second');

        $this->assertEquals(array(0 => 'first', 1 => 'second'), $collection->all());
    }

}
