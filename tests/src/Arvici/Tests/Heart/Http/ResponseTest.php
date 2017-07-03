<?php
/**
 * Response Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Http;

use App\TestUtils;
use Arvici\Exception\ResponseAlreadySendException;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * Response Test Case
 * @package Arvici\Tests\Heart\Http
 *
 * @coversDefaultClass \Arvici\Heart\Http\Response
 * @covers \Arvici\Heart\Http\Response
 */
class ResponseTest extends TestCase
{
    /**
     * Get fresh clean response class instance.
     *
     * @return \Arvici\Heart\Http\Response
     */
    private function getResponse()
    {
        TestUtils::clear();

        return Http::getInstance()->response();
    }

    /**
     * @covers \Arvici\Heart\Http\Http
     * @covers \Arvici\Heart\Http\Response
     */
    public function testConstruction()
    {
        $response = $this->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendingPlainBody()
    {
        ob_start();
        $response = $this->getResponse();

        $response->body('TEST');

        $this->assertEquals('TEST', $response->body());

        // Append
        $response->append('!');
        $response->prepend('FIRST_');

        $response->send();

        $body = ob_get_clean();
        $this->assertEquals('FIRST_TEST!', $body);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendingStatusCodes()
    {
        ob_start();
        $response = $this->getResponse();

        $response->send(500);

        $body = ob_get_clean();

        $this->assertEquals('', $body);
        $this->assertEquals(500, http_response_code());


        ob_start();
        $response = $this->getResponse();

        $response->code(500);
        $code = $response->status();
        $response->send();

        $body = ob_get_clean();

        $this->assertEquals('', $body);
        $this->assertEquals(500, http_response_code());
        $this->assertEquals(500, $code);
    }


    /**
     * @runInSeparateProcess
     */
    public function testJson()
    {
        ob_start();
        $response = $this->getResponse();

        $response->json(array('check' => true))->send();

        $body = ob_get_clean();
        $this->assertEquals('{"check":true}', $body);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        ob_start();
        $response = $this->getResponse();
        $response->redirect('http://www.google.com/')->send();
        ob_get_clean();
        $list = $response->headers();

        $this->assertArrayHasKey('Location', $list);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCache()
    {
        ob_start();
        $response = $this->getResponse();
        $response->cache(false)->send();
        ob_get_clean();

        $list = $response->headers();

        $this->assertArrayHasKey('Pragma', $list);
        $this->assertArrayHasKey('Cache-Control', $list);


        ob_start();
        $response = $this->getResponse();
        $response->cache(true)->send();
        ob_get_clean();

        $list = $response->headers();

        $this->assertArrayNotHasKey('Pragma', $list);
        $this->assertArrayNotHasKey('Cache-Control', $list);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendAgain()
    {
        ob_start();
        $response = $this->getResponse();
        $response->cache(false);
        $response->send();


        $this->assertTrue($response->isSent());


        // Try to set body
        try {
            $response->body('YE');
            $this->assertTrue(false);
        } catch (ResponseAlreadySendException $e) {
            $this->assertTrue(true);
        }


        // Try to send again..
        try {
            $response->send();
            $this->assertTrue(false);
        } catch (ResponseAlreadySendException $e) {
            $this->assertTrue(true);
        }

        ob_get_clean();
    }
}
