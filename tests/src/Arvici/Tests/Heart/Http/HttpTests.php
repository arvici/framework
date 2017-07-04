<?php
/**
 * Request Tests
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
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Request Test Case
 * @package Arvici\Tests\Heart\Http
 *
 * @coversDefaultClass \Arvici\Heart\Http\Http
 * @covers \Arvici\Heart\Http\Http
 */
class HttpTest extends TestCase
{
    private function spoof($url, $method, $get = array(), $post = array())
    {
        TestUtils::spoofUrl($url, $method, $get, $post);
    }

    /**
     * @covers \Arvici\Heart\Http\Http::getPsrRequest()
     * @covers \Arvici\Heart\Http\Http::getRequest()
     */
    public function getRequest()
    {
        $this->spoof('iets', 'GET');
        Http::clearInstance();
        $this->assertInstanceOf(Request::class, Http::getInstance()->getRequest());
        $this->assertInstanceOf(RequestInterface::class, Http::getInstance()->getPsrRequest());
    }

    /**
     * @covers \Arvici\Heart\Http\Http::getSession()
     */
    public function getSession()
    {
        $this->spoof('iets', 'GET');
        Http::clearInstance();
        $this->assertInstanceOf(Session::class, Http::getInstance()->getSession());
    }
}
