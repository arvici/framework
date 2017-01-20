<?php
/**
 * Arvici Exception
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Exception;


use Exception;

abstract class ArviciException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return static::class . ": [{$this->code}]: {$this->message}\n";
    }
}
