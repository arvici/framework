<?php
/**
 * ExceptionHandler
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Log;

use Arvici\Heart\Abilities\Registrable;
use Arvici\Heart\Config\Configuration;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Exception Handler for logging.
 */
class ExceptionHandler implements Registrable
{
    /** @var ExceptionHandler */
    private static $instance = null;

    /**
     * @return ExceptionHandler
     */
    private static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /** @var Run|false */
    private $whoops = false;

    public function __construct()
    {
        // Load in whoops on exception?
        $this->whoops = (Configuration::get('app.env', 'production') === 'development' && Configuration::get('app.visualException', false));

        if ($this->whoops) {
            $this->whoops = new Run();

            $pretty = new PrettyPageHandler();
            $pretty->setPageTitle("Arvici - Exception is thrown!");

            $this->whoops->pushHandler($pretty);
        }
    }


    /**
     * Register handler/ability.
     *
     * @param array $options
     *
     * @return mixed
     */
    public static function register($options = array())
    {
        $self = self::getInstance();

        error_reporting(-1);

        set_error_handler([$self, 'handleError']);
        set_exception_handler([$self, 'handleException']);
        register_shutdown_function([$self, 'handleShutdown']);

        if (Configuration::get('app.env', 'production') !== 'development') {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * Unregister handler/ability.
     *
     * @param array $options
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function unRegister($options = array())
    {
        throw new \Exception("Can't unregister exception handler!");
    }

    /**
     * Handle error thrown by PHP.
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @param array $context
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = array())
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }


    /**
     * Handle exception (uncatched).
     *
     * @param \Throwable $exception
     */
    public function handleException($exception)
    {
        if (! $exception instanceof \Exception) {
            $exception = new \Exception("Can't handle exception thrown!");
        }
        

        // Log
        \Logger::error((string) $exception);

        // Whoops
        if ($this->whoops !== false) {
            $this->whoops->handleException($exception);
        }
    }


    public function handleShutdown()
    {
        \Logger::isActive() && \Logger::debug('Arvici shutdown!');
    }
}