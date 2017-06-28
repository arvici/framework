<?php
/**
 * HttpException is the abstract exception class that can translate into a response code and message.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Exception\Response;

use Arvici\Exception\ArviciException;
use Arvici\Heart\Http\Http;
use Arvici\Heart\Http\Response;
use Exception;


class HttpException extends ArviciException
{
    protected $details;

    public function __construct($message, $code = 0, Exception $previous = null, $details = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * Converts the error to a response object.
     *
     * @return Response response object.
     */
    public function toResponse()
    {
        $response = Http::getInstance()->response();
        $response->reset();
        $response->status($this->code);
        if (is_string($this->details)) {
            $response->body($this->details);
        } elseif (is_string($this->message)) {
            $response->body($this->message);
        }

        return $response;
    }
}
