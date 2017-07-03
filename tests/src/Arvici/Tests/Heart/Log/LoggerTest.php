<?php
/**
 * LoggerTest.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Log;

use Arvici\Exception\LogException;
use Monolog\Handler\TestHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggerTest
 *
 * @package Arvici\Tests\Heart\Log
 *
 * @covers \Logger
 * @covers \Arvici\Heart\Log\Writer
 */
class LoggerTest extends TestCase
{
    /**
     * Test writing to log.
     *
     */
    public function testWrite()
    {
        \Logger::clearLog();

        \Logger::getInstance()->clearHandlers();

        \Logger::getInstance()->addHandler(new TestHandler());

        \Logger::log(\Logger::INFO, "TEST1-OK");
        \Logger::debug("TEST2-OK");

        $handlers = \Logger::getInstance()->getHandlers();
        $has = false;
        foreach ($handlers as $handler) {
            if ($handler instanceof TestHandler) {
                $has = true;

                $this->assertTrue($handler->hasInfo("TEST1-OK"));
                $this->assertTrue($handler->hasDebug("TEST2-OK"));
            }
        }

        $this->assertTrue($has);
    }


    /**
     * Test starting when already started.
     */
    public function testAlreadyStarted()
    {
        $this->assertTrue(\Logger::isActive());

        try {
            \Logger::start();
            $this->assertTrue(false);
        } catch (LogException $le) {
            $this->assertTrue(true);
        }
    }


}
