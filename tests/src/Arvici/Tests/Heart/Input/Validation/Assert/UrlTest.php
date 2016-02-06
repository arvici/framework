<?php
/**
 * UrlTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;


use Arvici\Heart\Input\Validation\Assert\Url;

/**
 * Class UrlTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Url
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function testUrl()
    {
        $url = new Url();

        $data = [
            'http://www.example.com',
            'www.example.com',
            'ftp://example.com',
            'tel:1234567890',
            ''
        ];

        $this->assertTrue($url->execute($data, 0));
        $this->assertFalse($url->execute($data, 1));
        $this->assertTrue($url->execute($data, 2));
        $this->assertFalse($url->execute($data, 3));
        $this->assertFalse($url->execute($data, 4));
    }
}
