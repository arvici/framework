<?php
/**
 * SQL Logger
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */
namespace Arvici\Heart\Log;

use Doctrine\DBAL\Logging\SQLLogger;


/**
 * Doctrine logging bridge.
 *
 * @package Arvici\Heart\Log
 *
 * @codeCoverageIgnore
 */
class DoctrineLogBridge implements SQLLogger
{
    protected $logger;
    protected $startTime;

    public function __construct(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Logs a SQL statement somewhere.
     *
     * @param string $sql The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->logger->addDebug($sql);
        $this->startTime = microtime(true);
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        $ms = round(((microtime(true) - $this->startTime) * 1000));
        $this->logger->addDebug("Query took {$ms}ms.");
    }
}
