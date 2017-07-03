<?php
/**
 * Request
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Http;
use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Collections\ParameterCollection;
use Arvici\Heart\Collections\ServerCollection;

/**
 * Request Handler
 *
 * @package Arvici\Heart\Http
 *
 * @api
 */
class Request
{
    /** @var string $unique Unique Request ID */
    protected $unique;

    /** @var string $base Base URL. */
    protected $base;
    /** @var ParameterCollection $getParams Get Parameters (query params) */
    protected $getParams;
    /** @var ParameterCollection $postParams Post parameters */
    protected $postParams;
    /** @var ParameterCollection $cookies Cookies */
    protected $cookies;
    /** @var ServerCollection $server Server */
    protected $server;
    /** @var DataCollection $headers Headers */
    protected $headers;
    /** @var array $body Body */
    protected $body;
    /** @var DataCollection $files Files */
    protected $files;

    /** @var Session $session */
    protected $session;

    /** @var Context $context */
    protected $context;

    /**
     * Request constructor.
     *
     * @param array $get
     * @param array $post
     * @param array $cookies
     * @param array $server
     * @param array $files
     * @param string $body
     * @param null $base
     * @param null|Session $session
     */
    public function __construct(
        array $get = array(),
        array $post = array(),
        array $cookies = array(),
        array $server = array(),
        array $files = array(),
        $body = null,
        $base = null,
        $session = null
    )
    {
        $this->getParams = new ParameterCollection($get);
        $this->postParams = new ParameterCollection($post);
        $this->cookies = new ParameterCollection($cookies);
        $this->server = new ServerCollection($server);
        $this->headers = new DataCollection($this->server->getHeaders());
        $this->files = new DataCollection($files);
        $this->body = is_string($body) ? $body : null;
        $this->base = $base;
        $this->session = $session;

        // Create the request context.
        $this->context = new Context();
    }

    /**
     * Detect from globals
     * @param Session $session
     * @return Request
     */
    public static function detect($session)
    {
        return new self ($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, null, null, $session);
    }

    /**
     * Get unique identifier for request
     *
     * @return string Unique String Identifier.
     */
    public function unique()
    {
        if ($this->unique === null) {
            $this->unique = uniqid();
        }
        return $this->unique;
    }

    /**
     * Search for parameter value
     * ORDER:
     *  - GET
     *  - POST
     *
     * @param string $key
     * @param mixed $default Default value when not found. Default null.
     *
     * @return mixed
     */
    public function param($key, $default = null)
    {
        if ($this->getParams->exists($key)) {
            return $this->getParams->get($key);
        }
        if ($this->postParams->exists($key)) {
            return $this->postParams->get($key);
        }

        return $default;
    }

    /**
     * Search for parameter value in GET query.
     *
     * @param string $key
     * @param mixed $default Default value when not found. Default null.
     *
     * @return mixed
     */
    public function paramGet($key, $default = null)
    {
        return $this->getParams->get($key, $default);
    }

    /**
     * Search for parameter value in POST array.
     *
     * @param string $key
     * @param mixed $default Default value when not found. Default null.
     *
     * @return mixed
     */
    public function paramPost($key, $default = null)
    {
        return $this->postParams->get($key, $default);
    }

    /**
     * Return the GET collection
     *
     * @return ParameterCollection
     */
    public function get()
    {
        return $this->getParams;
    }

    /**
     * Returns the POST collection
     *
     * @return ParameterCollection
     */
    public function post()
    {
        return $this->postParams;
    }

    /**
     * Returns the FILES collection (raw).
     *
     * @return DataCollection
     *
     * @codeCoverageIgnore Currently not in test scope
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Return SERVER collection
     *
     * @return ServerCollection
     */
    public function server()
    {
        return $this->server;
    }

    /**
     * Return Headers collection
     *
     * @return DataCollection
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Return COOKIE collection
     * @return ParameterCollection
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * Return merge of GET and POST parameters.
     *
     * @return array
     */
    public function params()
    {
        return array_merge($this->postParams->all(), $this->getParams->all());
    }

    /**
     * Is the request done over a secure protocol (HTTPS).
     *
     * @return bool
     */
    public function secure()
    {
        return ($this->server->get('HTTPS') == true);
    }

    /**
     * Get full user agent string
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function userAgent()
    {
        return $this->headers->get('USER_AGENT');
    }

    /**
     * Get ip.
     * WHEN BEHIND A REVERSE PROXY THIS WILL NOT WORK (YET).
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function ip()
    {
        return $this->server->get('REMOTE_ADDR');
    }

    /**
     * Get request URL
     *
     * @return string
     */
    public function url()
    {
        return $this->server->get('REQUEST_URI', '/');
    }

    /**
     * Get method name or test for method.
     *
     * @param null|string $assert Empty for returning method.
     *  fill in with one of the get/post/put/delete etc to test against it and return boolean if it's true.
     *
     * @return string|bool
     */
    public function method($assert = null)
    {
        $method = $this->server->get('REQUEST_METHOD', 'GET');

        if ($assert !== null && is_string($assert)) {
            return strtoupper($assert) === strtoupper($method);
        }
        return $method;
    }

    /**
     * Get request path name.
     *
     * @return string
     */
    public function path()
    {
        $url = $this->url();
        return strstr($url, '?', true) ? : $url; // Strip query and return
    }

    /**
     * Get Body contents (raw).
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function body()
    {
        if ($this->body == null) {
            $this->body = file_get_contents('php://input');
        }
        return $this->body;
    }

    /**
     * Get session instance.
     *
     * @return Session
     *
     * @codeCoverageIgnore
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * Get Request Context. You can store data that will be reachable for all middleware and controllers executing
     * or using the request.
     *
     * @return Context
     */
    public function context()
    {
        return $this->context;
    }
}
