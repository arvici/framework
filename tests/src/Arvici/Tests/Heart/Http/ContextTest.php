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
 * @covers \Arvici\Heart\Http\Request
 * @covers \Arvici\Heart\Http\Context
 */
class ContextTest extends TestCase
{
    /**
     * Get spoofed request class instance
     *
     * @param string $url
     * @param string $method
     * @param array $post
     * @return \Arvici\Heart\Http\Request
     */
    private function spoofedRequest($url, $method = 'get', $post = array())
    {
        TestUtils::clear();

        //$url = '/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3';
        $urlparts = parse_url($url);
        parse_str($urlparts['query'], $get);

        TestUtils::spoofUrl($url, $method, $get, $post);

        return Http::getInstance()->request();
    }


    public function testContext()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $context = $request->context();

        $this->assertInstanceOf(Context::class, $context);
    }

    public function testSettingContext()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $context = $request->context();
        $context->set('test', true);

        $this->assertInstanceOf(Context::class, $context);
        $this->assertEquals(true, $context->get('test'));
    }
}
