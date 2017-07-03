<?php
/**
 * Render Test Case (stack holder)
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Component\View;

use Arvici\Component\View\Builder;
use Arvici\Component\View\View;
use PHPUnit\Framework\TestCase;

/**
 * Render test
 *
 * @package Arvici\Tests\Component\View
 *
 * @coversDefaultClass \Arvici\Component\View\Render
 *
 * @covers \Arvici\Component\View\Render
 * @covers \Arvici\Component\View\View
 * @covers \Arvici\Component\View\Builder
 */
class RenderTest extends TestCase
{
    /** @var Builder */
    private $builder;

    private function clearBuilder()
    {
        $this->builder = View::build();
    }


    /**
     * @covers \Arvici\Heart\Renderer\PhpTemplate
     */
    public function testBasicRendering()
    {
        $this->clearBuilder();

        $html = $this->builder->loadStack('test-basicrender')->body('testbody1')->render(array('test' => 'worked'), true);

        $this->assertEquals("--HEADER----=worked=----FOOTER--", $html);
    }
}
