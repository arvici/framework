<?php
/**
 * Request Tests
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Http;

use App\TestUtils;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Collections\ParameterCollection;
use Arvici\Heart\Collections\ServerCollection;
use Arvici\Heart\Http\Http;

/**
 * Request Test Case
 * @package Arvici\Tests\Heart\Http
 *
 * @coversDefaultClass \Arvici\Heart\Http\Request
 * @covers \Arvici\Heart\Http\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase
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



    public function testGetParameters()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');

        // ====
        $get = array(
            'param1' => '1',
            'param2' => 'yes',
            'param3' => array(
                '1',
                '2',
                '3'
            )
        );

        $this->assertEquals($get, $request->get()->all());
        $this->assertNotNull($request->unique());

        // Parameter by param(), array parameter.
        $this->assertEquals(array('1', '2', '3'), $request->param('param3'));
        $this->assertEquals(array('1', '2', '3'), $request->paramGet('param3'));

        $this->assertFalse($request->param('nonexisting', false));
        $this->assertNull($request->param('nonext', null));
    }

    public function testPostParameters()
    {
        $post = array(
            'post1' => '1',
            'post2' => array(),
            'post3' => array(1,2,3),
            'param1' => '0'
        );

        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3', 'get', $post);
        // ====

        $this->assertEquals($post, $request->post()->all());
        $this->assertEquals('1', $request->param('param1')); // GET is always before POST
        $this->assertEquals($post['post3'], $request->param('post3'));
        $this->assertEquals($post['post1'], $request->paramPost('post1'));

        $this->assertCount(6, $request->params());
    }




    public function testUnique()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $unique1 = $request->unique();

        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $unique2 = $request->unique();

        $this->assertNotNull($unique1);
        $this->assertNotNull($unique2);

        $this->assertNotEquals($unique1, $unique2);
    }

    public function testServer()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $this->assertInstanceOf(ServerCollection::class, $request->server());
    }

    /**
     * @covers \Arvici\Heart\Collections\ServerCollection
     */
    public function testHeaders()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $this->assertInstanceOf(DataCollection::class, $request->headers());
        $this->assertEquals(array(), $request->server()->getHeaders());
    }

    public function testCookies()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $this->assertInstanceOf(ParameterCollection::class, $request->cookies());
        $this->assertTrue($request->cookies()->isEmpty());
    }

    public function testSecure()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $this->assertFalse($request->secure());

        $_SERVER['HTTPS'] = 1;
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3');
        $this->assertTrue($request->secure());
    }

    public function testUrl()
    {
        $url = '/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3';
        $request = $this->spoofedRequest($url);

        $this->assertEquals($url, $request->url());
    }

    public function testMethod()
    {
        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3', 'get');

        $this->assertEquals('GET', $request->method());
        $this->assertTrue($request->method('get'));
        $this->assertTrue($request->method('GET'));
        $this->assertFalse($request->method('POST'));
        $this->assertFalse($request->method(''));


        $request = $this->spoofedRequest('/get/test?param1=1&param2=yes&param3[]=1&param3[]=2&param3[]=3', 'post');

        $this->assertEquals('POST', $request->method());
        $this->assertTrue($request->method('post'));
        $this->assertTrue($request->method('POST'));
        $this->assertFalse($request->method('get'));
        $this->assertFalse($request->method('GET'));
        $this->assertFalse($request->method(''));
    }

    public function testPath()
    {
        $url = '/get/test?param1=1';
        $expected = '/get/test';

        $request = $this->spoofedRequest($url);

        $this->assertEquals($expected, $request->path());
    }
}
