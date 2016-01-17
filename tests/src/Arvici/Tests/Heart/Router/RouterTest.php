<?php
/**
 * Router Tests
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Router;
use Arvici\Exception\ControllerNotFoundException;
use Arvici\Heart\Router\Route;
use Arvici\Heart\Router\Router;

/**
 * Router Tests
 * @package Arvici\Tests\Heart\Router
 *
 * @coversDefaultClass \Arvici\Heart\Router\Router
 *
 * @covers \Arvici\Heart\Http\Http
 * @covers \Arvici\Heart\Router\Router
 * @covers \Arvici\Heart\Router\Route
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    private function spoof($url, $method)
    {
        $_SERVER['SCRIPT_NAME'] = 'index.php';
        $_SERVER['REQUEST_URI'] = $url;
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
    }

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     */
    public function testBasicGet()
    {
        $router = Router::getInstance();

        $done = false;
        $router->addRoute(new Route('/test/get', 'GET', function() use (&$done) {
            $done = true;
        }));

        $this->spoof('/test/get', 'GET');

        $router->run();

        $this->assertTrue($done);
    }

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     */
    public function testParameterGet()
    {
        $router = Router::getInstance();

        $done1 = false;
        $router->addRoute(new Route('/test/get/(!int)/(!int)', 'GET', function($num1, $num2) use (&$done1) {
            if ($num1 == 555 && $num2 == 111) {
                $done1 = true;
            }
        }));

        $this->spoof('/test/get/555/111', 'GET');
        $router->run();

        $this->assertTrue($done1);
    }

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     */
    public function testMultipleMethods()
    {
        $router = Router::getInstance();
        $router->clearRoutes();

        $done1 = 0;
        $router->addRoute(new Route('/test/all', array('GET', 'POST'), function() use (&$done1) {
            $done1++;
        }));

        $this->spoof('/test/all', 'GET');
        $router->run();

        $this->spoof('/test/all', 'POST');
        $router->run();

        $this->assertEquals(2, $done1);
    }

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     */
    public function testNonExistingController()
    {
        $router = Router::getInstance();
        $router->clearRoutes();

        $router->addRoute(new Route('/test/get/no/controller', array('GET', 'POST'), 'App\Controller\TestControllerDoesntExists::getNo'));

        $this->spoof('/test/get/no/controller', 'GET');

        try {
            $router->run();
            $this->assertTrue(false);
        }catch(ControllerNotFoundException $e) {
            $this->assertTrue(true);
        }
    }


    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     */
    public function testInvalidController()
    {
        $router = Router::getInstance();
        $router->clearRoutes();

        $router->addRoute(new Route('/test/get/existing/not/extending', 'GET', 'App\Controller\TestNoExtending::get'));
        $router->addRoute(new Route('/test/get/existing/non/existing/method', 'GET', 'App\Controller\TestNoMethod::get'));
        $router->addRoute(new Route('/test/get/prepare/stop', 'GET', 'App\Controller\TestPrepare::get'));

        $this->spoof('/test/get/existing/not/extending', 'GET');
        try {
            $router->run();
            $this->assertTrue(false);
        }catch (ControllerNotFoundException $e) {
            $this->assertTrue(true);
        }

        $this->spoof('/test/get/existing/non/existing/method', 'GET');
        try {
            $router->run();
            $this->assertTrue(false);
        }catch (ControllerNotFoundException $e) {
            $this->assertTrue(true);
        }


        $this->spoof('/test/get/prepare/stop', 'GET');
        $router->run();
    }

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     * @covers \Arvici\Component\Controller\BaseController
     * @covers \Arvici\Heart\Http\Http
     * @covers \Arvici\Heart\Http\Request
     * @covers \Arvici\Heart\Http\Response
     */
    public function testController()
    {
        $router = Router::getInstance();
        $router->clearRoutes();

        $router->addRoute(new Route('/test/get/controller/get', 'GET', 'App\Controller\TestCalled::get'));
        $router->addRoute(new Route('/test/get/controller/get/(!int)/(!int)', 'GET', 'App\Controller\TestCalled::getParameters1'));
        $router->addRoute(new Route('/test/get/controller/get/(!?)/(!int)', 'GET', 'App\Controller\TestCalled::getParameters2'));

        $this->spoof('/test/get/controller/get', 'GET');
        try {
            $router->run();
            $this->assertTrue(false);
        }catch (\Exception $e) {
            $this->assertEquals(999, $e->getCode());
        }




        $this->spoof('/test/get/controller/get/11/54', 'GET');
        try {
            $router->run();
            $this->assertTrue(false);
        }catch (\Exception $e) {
            $this->assertEquals(999, $e->getCode());
        }



        $this->spoof('/test/get/controller/get/test/555', 'GET');
        try {
            $router->run();
            $this->assertTrue(false);
        }catch (\Exception $e) {
            $this->assertEquals(999, $e->getCode());
        }
    }
}
