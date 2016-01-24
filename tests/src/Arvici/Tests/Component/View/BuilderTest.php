<?php
/**
 * View Builder Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Component\View;
use Arvici\Component\View\Builder;
use Arvici\Component\View\Render;
use Arvici\Component\View\View;
use Arvici\Exception\RendererException;

/**
 * Class BuilderTest
 * @package Arvici\Tests\Component\View
 *
 * @coversDefaultClass \Arvici\Component\View\Builder
 *
 * @covers \Arvici\Component\View\Builder
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Builder */
    private $builder;

    private function clearBuilder()
    {
        $this->builder = View::build();
        $this->builder->clear();
    }

    /**
     * @covers \Arvici\Component\View\View::build()
     * @covers ::__construct()
     */
    public function testPrepareBuilder()
    {
        $this->clearBuilder();

        $this->assertInstanceOf("\\Arvici\\Component\\View\\Builder", $this->builder);
    }

    /**
     * @covers ::defaultStack()
     * @covers \Arvici\Component\View\Render
     */
    public function testLoadDefaultStack()
    {
        $this->clearBuilder();

        $this->builder->defaultStack();

        $stack = Render::getInstance()->raw();

        $this->assertCount(3, $stack);
    }

    /**
     * @covers ::loadStack()
     * @covers \Arvici\Component\View\Render
     */
    public function testLoadCustomStack()
    {
        $this->clearBuilder();

        $this->builder->loadStack('test-sample');

        $stack = Render::getInstance()->raw();

        $this->assertCount(4, $stack);
        $this->assertEquals(View::PART_BODY_PLACEHOLDER, $stack[1]->getType());
        $this->assertEquals(View::PART_BODY, $stack[2]->getType());

        // Invalid
        try {
            $this->builder->loadStack('NONEXISTING');
            $this->assertTrue(false);
        } catch (RendererException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @covers ::loadStack()
     * @covers ::addView()
     * @covers ::body()
     * @covers ::template()
     * @covers ::clear()
     * @covers \Arvici\Component\View\Render
     */
    public function testAddingViews()
    {
        $this->clearBuilder();

        $this->builder->template('header');
        $this->builder->addView(new View(null, View::PART_BODY_PLACEHOLDER)); // Placeholder
        $this->builder->body('replaced');
        $this->builder->body('added');
        $this->builder->template('footer');

        $stack = Render::getInstance()->raw();

        $this->assertCount(4, $stack);

        $paths = array("header", "replaced", "added", "footer");

        foreach ($stack as $idx => $view) {
            $this->assertEquals($paths[$idx], $view->getPath());
        }
    }

    /**
     * @covers ::addView()
     * @covers ::body()
     * @covers ::template()
     * @covers ::clear()
     * @covers \Arvici\Component\View\Render
     */
    public function testInvalidBody()
    {
        $this->clearBuilder();

        $this->builder->defaultStack();

        try {
            Render::getInstance()->body(new View('invalid', View::PART_TEMPLATE));
            $this->assertTrue(false);
        } catch (RendererException $re) {
            $this->assertTrue(true);
        }


        $this->builder->clear();
        try {
            Render::getInstance()->body(new View('valid_but_no_body_placeholder', View::PART_BODY));
            $this->assertTrue(false);
        } catch (RendererException $re) {
            $this->assertTrue(true);
        }
    }
}
