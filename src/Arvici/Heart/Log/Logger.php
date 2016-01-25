<?php
/**
 * Logger
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

use Arvici\Exception\ConfigurationException;
use Arvici\Exception\LogException;
use Arvici\Exception\PermissionDeniedException;
use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Log\ExceptionHandler;
use Arvici\Heart\Log\Writer;
use Monolog\Handler\StreamHandler;

/**
 * Public Logger Class
 *
 * WILL BE IN THE ROOT NAMESPACE!
 * Use it like this: \Log::debug('message');
 *
 * Alternative you can use the \Log::getInstance(); to get a PSR-3 logger!
 *
 * @package Arvici\Heart\Log
 */
class Logger
{

    // Re-define level constants.
    const DEBUG = \Monolog\Logger::DEBUG;
    const INFO = \Monolog\Logger::INFO;
    const NOTICE = \Monolog\Logger::NOTICE;
    const WARNING = \Monolog\Logger::WARNING;
    const ERROR = \Monolog\Logger::ERROR;
    const CRITICAL = \Monolog\Logger::CRITICAL;
    const ALERT = \Monolog\Logger::ALERT;
    const EMERGENCY = \Monolog\Logger::EMERGENCY;

    /**
     * @var Writer
     */
    private static $instance = null;

    /**
     * @var array
     */
    private static $files = array();

    /**
     * @var string
     */
    private static $path = null;

    /**
     * Start function, will be called from the bootstrap file.
     */
    public static function start()
    {
        if (self::$instance !== null) {
            throw new LogException("Logger already started!");
        }

        if (Configuration::get('app.log', true)) {
            // Get configuration
            $logPath = Configuration::get('app.logPath');
            if ($logPath === null) {// @codeCoverageIgnore
                throw new ConfigurationException("app.logPath is not valid!");  // @codeCoverageIgnore
            }                       // @codeCoverageIgnore

            if (! file_exists($logPath)) {  // @codeCoverageIgnore
                $make = mkdir($logPath);    // @codeCoverageIgnore
                if (! $make) {              // @codeCoverageIgnore
                    throw new PermissionDeniedException("Could not create directory '".$logPath."'!");  // @codeCoverageIgnore
                }                           // @codeCoverageIgnore
            }                               // @codeCoverageIgnore

            // Get all level and log files.
            self::$files = Configuration::get('app.logFile', ['app.log' => self::ERROR]);

            // Load the logger
            self::$instance = new Writer($logPath);

            self::$path = $logPath;

            // Load all handlers.
            foreach (self::$files as $file => $minimumLevel) {
                self::$instance->addHandler(new StreamHandler($logPath . $file, $minimumLevel));
            }

            // Write debug message for starting logger
            self::log(self::DEBUG, 'Arvici Logger Started!');

            // Register exception/error handler
            ExceptionHandler::register();
        }
    }

    /**
     * Is the logger active? Can it be used?
     * @return bool
     */
    public static function isActive()
    {
        return self::$instance !== null;
    }

    /**
     * Get Log Writer instance. Will be PSR-3 compatible!
     *
     * @return Writer
     * @throws LogException
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            throw new LogException("Logger is not started!");
        }

        return self::$instance;
    }

    /**
     * Clear and delete log files.
     *
     * @param mixed|null $level Null for every level (default).
     */
    public static function clearLog($level = null)
    {
        foreach (self::$files as $fileName => $fileLevel) {
            if ($level === null || $level === $fileLevel) {
                $fileName = self::$path . $fileName;

                if (file_exists($fileName)) {
                    unlink($fileName);
                }
                continue;
            }
        }

        // Restart current logger instance.
        self::$instance = null;
        self::start();
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @throws LogException
     */
    public static function log($level, $message, array $context = array())
    {
        return self::getInstance()->log($level, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @codeCoverageIgnore
     */
    public static function debug($message, array $context = array())
    {
        return self::log(self::DEBUG, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public static function info($message, array $context = array())
    {
        return self::log(self::INFO, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public static function notice($message, array $context = array())
    {
        return self::log(self::NOTICE, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public static function warning($message, array $context = array())
    {
        return self::log(self::WARNING, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public static function error($message, array $context = array())
    {
        return self::log(self::ERROR, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public function critical($message, array $context = array())
    {
        return self::log(self::CRITICAL, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public function alert($message, array $context = array())
    {
        return self::log(self::ALERT, $message, $context);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     *
     * @codeCoverageIgnore
     */
    public function emergency($message, array $context = array())
    {
        return self::log(self::EMERGENCY, $message, $context);
    }
}