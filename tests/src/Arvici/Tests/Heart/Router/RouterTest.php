<?php
/**
 * Router Tests
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Tests\Heart\Router;

use App\TestUtils;
use Arvici\Exception\ControllerNotFoundException;
use Arvici\Exception\RouterException;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Router\Route;
use Arvici\Component\Router;

/**
 * Router Tests
 * @package Arvici\Tests\Heart\Router
 *
 * @coversDefaultClass \Arvici\Heart\Router\Router
 *
 * @covers \Arvici\Heart\Http\Http
 * @covers \Arvici\Heart\Router\Router
 * @covers \Arvici\Component\Router
 * @covers \Arvici\Heart\Router\Route
 * @covers \Arvici\Heart\Router\Middleware
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    private function spoof($url, $method, $get = array(), $post = array())
    {
        TestUtils::spoofUrl($url, $method, $get, $post);
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
        TestUtils::clear();
        $router = Router::getInstance();

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
        TestUtils::clear();
        $router = Router::getInstance();


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
        TestUtils::clear();
        $router = Router::getInstance();

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
        TestUtils::clear();
        $router = Router::getInstance();

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

    /**
     * @covers \Arvici\Heart\Router\Router
     * @covers \Arvici\Heart\Router\Route
     * @covers \Arvici\Component\Controller\BaseController
     * @covers \Arvici\Heart\Http\Http
     * @covers \Arvici\Heart\Http\Request
     * @covers \Arvici\Heart\Http\Response
     */
    public function testQueryParameters()
    {
        TestUtils::clear();
        $router = Router::getInstance();

        $router->addRoute(new Route('/test/get/controller/query', 'GET', 'App\Controller\TestQuery::basicQuery'));

        $this->spoof('/test/get/controller/query?test=yes', 'GET', array('test' => 'yes'));

        try {
            $router->run();
            $this->assertTrue(false);
        }catch(\Exception $e) {
            $this->assertEquals(999, $e->getCode());
        }

        // Test via request object (right here)
        $request = Http::getInstance()->request();
        $this->assertEquals(array('test' => 'yes'), $request->get()->all());

        // More testing on REQUEST is done in a separate case
    }


    /**
     * Test the define method.
     */
    public function testDefine()
    {
        TestUtils::clearRoutes();

        $done = 0;

        Router::define(function(Router $router) use (&$done) {
            $router->get("/test/get/define/1", function() use (&$done) {
                $done++;
            });;

            $router->get("/test/get/define/2", function() use (&$done) {
                $done++;
            });;
        });

        $router = Router::getInstance();

        $this->spoof('/test/get/define/1', 'GET');
        $router->run();

        $this->spoof('/test/get/define/2', 'GET');
        $router->run();

        $this->assertEquals(2, $done);
    }


    /**
     * Test group define and middleware for group.
     */
    public function testGroup()
    {
        TestUtils::clearRoutes();

        // Called?
        $globalBefore = false;
        $globalBeforePost = false;
        $globalAfter = false;

        $groupBefore = false;
        $groupAfter = false;

        // Done int.
        $done = 0;

        // Hold if we already called.
        $called = false;


        // Set global middleware, before.
        Router::getInstance()->before(function() use (&$globalBefore) {
            $globalBefore = true;
        });

        Router::getInstance()->before(function() use (&$globalBeforePost) {
            $globalBeforePost = true;
        }, null, ['POST']);

        Router::getInstance()->after(function() use (&$globalAfter) {
            $globalAfter = true;
        });

        // Define group.
        Router::group('group1', function(Router $router) use (&$done, &$called, &$groupBefore, &$groupAfter) {
            // Add before that will only pass 1 time (the first time)
            $router->before(function() use (&$called, &$groupBefore) {
                $groupBefore = true;

                if ($called) {
                    return false;
                }
                $called = true;
                return true;
            });

            // Add test get
            $router->get('/middleware/1', function() use (&$done) {
                $done++;
            });

            // Add test after
            $router->after(function() use (&$groupAfter) {
                $groupAfter = true;
            });
        });

        Router::getInstance()->post("/middleware/2", function() use (&$done) {
            $done++;
        });


        // Ok, lets spoof.
        $router = Router::getInstance();

        $this->spoof('/middleware/1', 'GET');
        $router->run();

        $this->assertEquals(1, $done);
        $this->assertTrue($globalBefore);
        $this->assertTrue($globalAfter);
        $this->assertTrue($groupBefore);
        $this->assertTrue($groupAfter);
        $this->assertFalse($globalBeforePost);


        // Second time should not call the $done one again!
        $this->spoof('/middleware/1', 'GET');
        $router->run();

        $this->assertEquals(1, $done);
        $this->assertTrue($globalBefore);
        $this->assertTrue($globalAfter);
        $this->assertTrue($groupBefore);
        $this->assertTrue($groupAfter);
        $this->assertFalse($globalBeforePost);


        // Call the post one!
        $this->spoof('/middleware/2', 'POST');
        $router->run();

        $this->assertEquals(2, $done);
        $this->assertTrue($globalBefore);
        $this->assertTrue($globalAfter);
        $this->assertTrue($groupBefore);
        $this->assertTrue($groupAfter);
        $this->assertTrue($globalBeforePost);
    }




    public function testClassMiddleware()
    {
        TestUtils::clearRoutes();

        $done = 0;
        Router::define(function(Router $router) use (&$done) {
            $router->get("/middleware/1", function() use (&$done) {
                $done++;
            });
            $router->post("/middleware/2", function() use (&$done) {
                $done++;
            });
            $router->put("/middleware/3", function() use (&$done) {
                $done++;
            });
        });

        // Middleware in class (controller)
        // Invalid, non existing class
        Router::getInstance()->after('App\Middleware\TestMiddlewareNotExisting::go', null, ['POST']);

        // Invalid, non existing method
        Router::getInstance()->before('App\Middleware\TestMiddleware::nonexisting', null, ['GET']);

        // Valid
        Router::getInstance()->before('App\Middleware\TestMiddleware::testThrow', null, ['PUT']);

        // Spoof
        $this->spoof('/middleware/2', 'POST');

        try {
            Router::getInstance()->run();
            $this->assertTrue(false);
        } catch (RouterException $re) {
            $this->assertTrue(true);
        }

        // Spoof
        $this->spoof('/middleware/1', 'GET');

        try {
            Router::getInstance()->run();
            $this->assertTrue(false);
        } catch (RouterException $re) {
            $this->assertTrue(true);
        }

        // Spoof
        $this->spoof('/middleware/3', 'PUT');
        try {
            Router::getInstance()->run();
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertEquals("TEST", $e->getMessage());
        }

        $this->assertEquals(1, $done);
    }
}
