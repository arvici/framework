<?php
/**
 * TwigTemplateTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Heart\Renderer\MustacheTemplate;
use Arvici\Heart\Renderer\TwigTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class TwigTemplateTest
 * @package Arvici\Tests\Heart\Renderer
 *
 * @coversDefaultClass \Arvici\Heart\Renderer\TwigTemplate
 * @covers \Arvici\Heart\Renderer\TwigTemplate
 */
class TwigTemplateTest extends TestCase
{
    /**
     * @covers \Arvici\Heart\Renderer\TwigTemplate
     */
    public function testBasicRendering()
    {
        $renderer = new TwigTemplate();

        $renderer->setData(array('first' => 'yes'));

        $output = $renderer->render(new View('twig_test1.twig', View::PART_BODY, 'TwigTemplate'));

        $this->assertContains("Test 2, yes", $output);

        $output = $renderer->render(new View('twig_test1', View::PART_BODY, 'TwigTemplate'));

        $this->assertContains("Test 2, yes", $output);
    }
}
