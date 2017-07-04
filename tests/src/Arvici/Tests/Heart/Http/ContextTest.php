<?php
/**
 * Context Tests
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Http;

use App\TestUtils;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Collections\ParameterCollection;
use Arvici\Heart\Collections\ServerCollection;
use Arvici\Heart\Http\Context;
use Arvici\Heart\Http\Http;
use PHPUnit\Framework\TestCase;

/**
 * Request Context Test Case
 * @package Arvici\Tests\Heart\Http
 *
 * @coversDefaultClass \Arvici\Heart\Http\Context
 * @covers \Arvici\Heart\Http\Context
 * @covers \Arvici\Heart\Http\Http
 */
class ContextTest extends TestCase
{
    public function testContext()
    {
        Http::clearInstance();
        $context = Http::getInstance()->getContext();


        $this->assertInstanceOf(Context::class, $context);
    }

    public function testSettingContext()
    {
        Http::clearInstance();
        $context = Http::getInstance()->getContext();

        $context->set('test', true);

        $this->assertInstanceOf(Context::class, $context);
        $this->assertEquals(true, $context->get('test'));
    }
}
