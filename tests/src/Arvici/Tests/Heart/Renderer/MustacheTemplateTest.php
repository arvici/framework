<?php
/**
 * MustacheTemplateTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Renderer;

use Arvici\Component\View\View;
use Arvici\Heart\Renderer\MustacheTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class MustacheTemplateTest
 * @package Arvici\Tests\Heart\Renderer
 *
 * @coversDefaultClass \Arvici\Heart\Renderer\MustacheTemplate
 *
 * @covers \Arvici\Heart\Renderer\MustacheTemplate
 */
class MustacheTemplateTest extends TestCase
{
    /**
     * @covers \Arvici\Heart\Renderer\MustacheTemplate
     */
    public function testBasicRendering()
    {
        $renderer = new MustacheTemplate();

        $renderer->setData(array('first' => 'yes'));

        $output = $renderer->render(new View('mustache_test1', View::PART_BODY, 'MustacheTemplate'));

        $this->assertEquals("Test 1, yes", $output);
    }
}
