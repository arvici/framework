<?php
/**
 * Response
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Http;
use Arvici\Exception\ResponseAlreadySendException;
use Arvici\Heart\Collections\DataCollection;

/**
 * Response Handler
 *
 * @package Arvici\Heart\Http
 *
 * @api
 */
class Response
{
    /**
     * Headers.
     * @var DataCollection
     */
    protected $headers = array();

    /**
     * Response Code (http code).
     * @var int
     */
    protected $code = 200;

    /**
     * Body to sent with the response
     *
     * @var string
     */
    protected $body;

    /**
     * Cookies to sent
     *
     * @var DataCollection<Cookie>
     */
    protected $cookies;

    /**
     * Is the response already sent? If we already sent it, we will lock the response!
     *
     * @var bool
     */
    protected $sent = false;

    /**
     * Response Constructor.
     * Don't call this directly, use the controller or service providers!
     *
     * @param string $body
     */
    public function __construct($body = '')
    {
        $this->send = false;
        $this->body($body);

        $this->headers = new DataCollection();
        $this->cookies = new DataCollection();
    }

    /**
     * Set body, or get when body parameter is null.
     *
     * @param null $body
     * @return Response|string
     *
     * @throws ResponseAlreadySendException
     */
    public function body($body = null)
    {
        if ($body === null) { // @codeCoverageIgnore
            return $this->body; // @codeCoverageIgnore
        }

        $this->requireUnsent();

        $this->body = $body;

        return $this;
    }

    /**
     * Set or get the status code (http code).
     *
     * @param int|null $code Code, null for getting the code
     * @return Response|int
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function status($code = null)
    {
        if ($code === null || ! is_int($code)) {
            return $this->code;
        }

        $this->requireUnsent();

        $this->code = $code;

        return $this;
    }

    /**
     * Set or get the status code (http code).
     *
     * @param int|null $code Code, null for getting the code
     * @return Response|int
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function code($code = null)
    {
        return $this->status($code);
    }

    /**
     * Get cookies
     * @return DataCollection
     *
     * @codeCoverageIgnore
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * Get headers
     * @return DataCollection
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Append data to the body.
     *
     * @param string $data
     *
     * @return Response
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function append($data)
    {
        $this->requireUnsent();

        $this->body .= $data;

        return $this;
    }

    /**
     * Prepend data to the body.
     *
     * @param string $data
     *
     * @return Response
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function prepend($data)
    {
        $this->requireUnsent();

        $this->body = $data . $this->body;

        return $this;
    }

    /**
     * Parse json for sending.
     *
     * @param mixed $object
     * @param bool $pretty Pretty Print , default false.
     * @return Response
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function json($object, $pretty = false)
    {
        $this->header('Content-Type', 'application/json');
        return $this->body(json_encode($object, $pretty ? JSON_PRETTY_PRINT : 0));
    }

    /**
     * Will send redirect, if stop is true, we will send and exit the application!
     *
     * @param string $url
     * @param bool $stop Send and stop application!
     * @param int $code Http code used for redirect
     *
     * @return Response
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function redirect($url, $stop = false, $code = 302)
    {
        $this->requireUnsent();

        $this->header('Location', $url);
        $this->code($code);

        if ($stop) {
            $this->send();  // @codeCoverageIgnore
            exit();         // @codeCoverageIgnore
        }

        return $this;
    }

    /**
     * Is response already sent
     *
     * @return bool
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Add header.
     *
     * @param string $key
     * @param mixed $value
     * @return Response
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function header($key, $value)
    {
        $this->headers->set($key, $value);
        return $this;
    }

    /**
     * Add cookie
     *
     * @param string $name
     * @param mixed $value
     * @param int $expiry
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     *
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function cookie($name, $value, $expiry = null, $domain = null, $path = '/', $secure = false, $httpOnly = false)
    {
        if ($expiry === null) {
            $expiry = time() + (60 * 60 * 24 * 30); // 30 days, default.
        }
        $cookie = new Cookie($name, $value, $expiry, $path, $domain, $httpOnly, $secure);
        $this->cookies->set($name, $cookie);

        return $this;
    }

    /**
     * Enable (or disable when false is given) the cache.
     *
     * @param bool $enabled default true.
     *
     * @return Response
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function cache($enabled = true)
    {
        if (! $enabled) {
            $this->header('Pragma', 'no-cache');
            $this->header('Cache-Control', 'no-store, no-cache');

            return $this;
        }
        $this->headers->remove('Pragma');
        $this->headers->remove('Cache-Control');

        return $this;
    }


    /**
     * Send response to the client.
     *
     * @param int $code Override the response code send to the client.
     * @param bool $force Force, don't check if headers are already sent.
     * @return Response
     *
     * @throws ResponseAlreadySendException
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function send($code = null, $force = false)
    {
        $this->requireUnsent();

        if (!$force && headers_sent()) {
            throw new ResponseAlreadySendException("Some response already sent, make sure you don't set the headers directly, or output something before calling response object!"); // @codeCoverageIgnore
        }

        if ($code !== null) {
            $this->code($code);
        }

        // Try to send headers first.
        $this->sendHeaders();
        $this->sendBody();

        $this->sent = true;

        return $this;
    }


    /**
     * Reset the response headers, body, etc. Useful when catching error.
     *
     * @codeCoverageIgnore
     */
    public function reset()
    {
        $this->headers = new DataCollection();
        $this->body = '';
        $this->sent = false;
    }


    /**
     * Send Headers and Cookies
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    protected function sendHeaders()
    {
        // Set the response code
        http_response_code($this->code);

        // Send headers
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, false);
        }

        // Set cookies
        foreach ($this->cookies as $name => $value) { /** @var Cookie $value */
            setcookie($name, $value->getValue(), $value->getExpiry(), $value->getPath(), $value->getDomain(), $value->isSecure(), $value->isHttpOnly()); // @codeCoverageIgnore
        }
    }

    /**
     * Send Body
     *
     * @codeCoverageIgnore
     */
    protected function sendBody()
    {
        echo $this->body;
    }

    /**
     * Check for current response, if it's already sent, or any headers are already sent.
     * @throws ResponseAlreadySendException
     */
    protected function requireUnsent()
    {
        if ($this->sent) { // @codeCoverageIgnore
            throw new ResponseAlreadySendException("Current response already sent!"); // @codeCoverageIgnore
        }
    }
}
