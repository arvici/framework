<?php
/**
 * Bootstrapper.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart;


use Arvici\Component\Router;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Http\Http;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Bootstrapper class.
 *
 * @package Arvici\Heart
 * @codeCoverageIgnore
 */
class Bootstrapper
{
    /** @var Http $http Http Instance */
    private $http;

    /** @var Router $router Router Instance */
    private $router;

    /**
     * Bootstrapper constructor.
     */
    public function __construct()
    {
        $this->http = Http::getInstance();
        $this->router = Router::getInstance();
    }

    private function startBase()
    {
        // Set timezone.
        date_default_timezone_set(Configuration::get('app.timezone', 'UTC'));

        // Start logger
        \Logger::start();
    }

    /**
     * Start from web, will make sure we start the logger, some kernel parts, and the router.
     */
    public function startWeb()
    {
        // Start the base.
        $this->startBase();

        // Start router and start parsing request.
        $this->router->run();
    }

    public function startCli()
    {
        // Start the base.
        $this->startBase();
    }

    /**
     * Start from test (unit).
     */
    public function startTest()
    {
        // Start the base.
        $this->startBase();

        // Test logger to console.
        \Logger::clearLog();
        \Logger::getInstance()->addHandler(new \Monolog\Handler\TestHandler());
        \Logger::debug('PHPUnit Tests Started...');
    }
}
