<?php
/**
 * App Test Case
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\App;

use Arvici\Exception\AlreadyInitiatedException;
use Arvici\Heart\App\AppManager;
use Arvici\Heart\App\BaseApp;
use PHPUnit\Framework\TestCase;


/**
 * App Test Case
 *
 * @package Arvici\Tests\Heart\App
 *
 * @coversDefaultClass \Arvici\Heart\App\AppManager
 * @covers \Arvici\Heart\App\BaseApp
 * @covers \Arvici\Heart\App\AppManager
 * @covers \Arvici\Heart\Config\Configuration
 */
class AppTest extends TestCase
{
    public function testAppCreation ()
    {
        $appManager = AppManager::getInstance();

        $this->assertNotNull($appManager);
        $this->assertInstanceOf(AppManager::class, $appManager);
    }

    public function testAppInitialization ()
    {
        $appManager = AppManager::getInstance();
        $appManager->initApps();

        $this->assertCount(1, $appManager->getApps());
        $this->assertInstanceOf(BaseApp::class, $appManager->getApps()[0]);

        try {
            $appManager->initApps();
            $this->assertTrue(false);
        } catch (AlreadyInitiatedException $exception) {
            $this->assertTrue(true);
        }
    }
}
