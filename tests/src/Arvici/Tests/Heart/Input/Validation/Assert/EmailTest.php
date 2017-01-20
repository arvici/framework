<?php
/**
 * EmailTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation\Assert;

use Arvici\Heart\Input\Validation\Assert\Contain;
use Arvici\Heart\Input\Validation\Assert\Email;

/**
 * Class ContainTest
 * @package Arvici\Tests\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Email
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testEmail()
    {
        $email = new Email();

        $data = [
            'invalid@domain',
            'valid@example.com',
            'valid@newdomain.awesome',
            'valid@δοκιμή.test', // KNOWN BUG IN PHP. IDN domain names not working currently
            'invalid',
            false,
            0
        ];

        $this->assertFalse($email->execute($data, 0));
        $this->assertTrue($email->execute($data, 1));
        $this->assertTrue($email->execute($data, 2));
        // $this->assertTrue($email->execute($data, 3));
        $this->assertFalse($email->execute($data, 4));
        $this->assertFalse($email->execute($data, 5));
        $this->assertFalse($email->execute($data, 6));
    }
}
