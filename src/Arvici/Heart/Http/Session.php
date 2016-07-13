<?php
/**
 * Session
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Http;

use Arvici\Exception\AlreadyInitiatedException;
use Arvici\Heart\Config\Configuration;

/**
 * Session
 * @package Arvici\Heart\Http
 *
 * @codeCoverageIgnore
 */
class Session
{
    /**
     * @var bool
     */
    private $init = false;

    /**
     * @var bool
     */
    private $started = false;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $prefix = 'arvici_';

    public function __construct ()
    {}

    /**
     * Use internally only!
     *
     * @throws AlreadyInitiatedException
     */
    public function init ()
    {
        if ($this->init)
            throw new AlreadyInitiatedException('Session is already initiated and started!');
        $this->init = true;

        // Get configuration for session.
        $this->config = Configuration::get('app.session', []);

        // Verify and defaulting config.
        if ($this->config === false) return; // Session is disabled, don't start it!

        if (! isset($this->config['name']))   $this->config['name'] = 'arvici_session';
        if (! isset($this->config['expire'])) $this->config['expire'] = 1*60*60; // 1 hour default.
        if (! isset($this->config['path']))   $this->config['path'] = '/';
        if (! isset($this->config['domain'])) $this->config['domain'] = null;
        if (! isset($this->config['secure'])) $this->config['secure'] = false; // Secure only flag.
        if (! isset($this->config['http']))   $this->config['http'] = true; // Http Only flag.

        if (isset($this->config['prefix']))   $this->prefix = $this->config['prefix'];

        if (session_status() === PHP_SESSION_ACTIVE)
            throw new AlreadyInitiatedException('Session is already started before Arvici is started!');

        $this->start();
    }

    /**
     * Start the session.
     */
    private function start ()
    {
        session_name($this->config['name']);
        session_set_cookie_params($this->config['expire'], $this->config['path'], $this->config['domain'],
            $this->config['secure'], $this->config['http']);
        session_start();

        $this->started = true;
    }

    /**
     * Is session started?
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    public function set ($key, $value = null)
    {
        // If the key is an array, we are going to set multiple key=>value entries from the $key value.
        if (is_array($key) && $value === null) {
            foreach ($key as $item => $value) {
                $this->set($item, $value);
            }
            return;
        }

        $_SESSION[$this->prefix . $key] = $value;
    }

    public function pull ($key, $default = null)
    {
        if (! isset($_SESSION[$this->prefix . $key]))
            return $default;

        // Retrieve value, unset and return.
        $value = $_SESSION[$this->prefix . $key];
        unset($_SESSION[$this->prefix . $key]);

        return $value;
    }

    public function get($key, $default = null)
    {
        if (! isset($_SESSION[$this->prefix . $key]))
            return $default;

        // Retrieve value and return.
        return $_SESSION[$this->prefix . $key];
    }

    /**
     * Get session id.
     *
     * @return string with the session id.
     */
    public function id()
    {
        return session_id();
    }

    /**
     * Regenerate session_id.
     *
     * @param boolean $deleteOld Delete old session file/storage.
     * @return string session_id
     */
    public function regenerate($deleteOld = true)
    {
        session_regenerate_id($deleteOld);
        return session_id();
    }

    /**
     * Return the session array.
     *
     * @return array of session indexes
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * Empty and destroys the current session.
     *
     * @param string $key Optional session name. Ignore to destroy all contents.
     */
    public function destroy($key = null)
    {
        if ($key === null) {
            session_unset();

            if (session_id() !== "")
                session_destroy();

            return;
        }

        unset($_SESSION[$this->prefix . $key]);
    }
}
