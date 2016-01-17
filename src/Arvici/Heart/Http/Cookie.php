<?php
/**
 * Cookie
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Http;

/**
 * Cookie
 * @package Arvici\Heart\Http
 *
 * @codeCoverageIgnore
 */
class Cookie
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $expiry;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $httpOnly;

    /**
     * @var bool
     */
    protected $secure;


    /**
     * Cookie constructor.
     * @param string $name
     * @param string $value
     * @param int $expiry
     * @param string $path
     * @param string $domain
     * @param bool $httpOnly
     * @param bool $secure
     */
    public function __construct(
        $name,
        $value,
        $expiry,
        $path,
        $domain,
        $httpOnly,
        $secure
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->expiry = $expiry;
        $this->path = $path;
        $this->domain = $domain;
        $this->httpOnly = $httpOnly;
        $this->secure = $secure;
    }



    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return boolean
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }



}