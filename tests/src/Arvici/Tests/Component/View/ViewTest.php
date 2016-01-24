<?php
/**
 * ViewTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Component\View;

use Arvici\Component\View\View;

/**
 * Class ViewTest
 * @package Arvici\Tests\Component\View
 *
 * @covers \Arvici\Component\View\View
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Arvici\Component\View\View
     * @covers \Arvici\Component\View\View::template
     * @covers \Arvici\Component\View\View::body
     * @covers \Arvici\Component\View\View::bodyPlaceholder
     * @covers \Arvici\Component\View\View::getPath
     */
    public function testBasicView()
    {
        $view = View::template('template');
        $this->assertInstanceOf("\\Arvici\\Component\\View\\View", $view);
        $this->assertEquals(View::PART_TEMPLATE, $view->getType());

        $view = View::body('body');
        $this->assertInstanceOf("\\Arvici\\Component\\View\\View", $view);
        $this->assertEquals(View::PART_BODY, $view->getType());

        $this->assertEquals('body', $view->getPath());


        $view = View::bodyPlaceholder();
        $this->assertInstanceOf("\\Arvici\\Component\\View\\View", $view);
        $this->assertEquals(View::PART_BODY_PLACEHOLDER, $view->getType());

        $this->assertEquals(null, $view->getPath());
    }
}