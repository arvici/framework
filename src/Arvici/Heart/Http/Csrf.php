<?php
/**
 * Csrf Class
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Http;

/**
 * Class Csrf
 * @package Arvici\Heart\Http
 *
 * @codeCoverageIgnore
 */
class Csrf
{
    /**
     * Create new Csrf Token and save it in the session for validation.
     *
     * @param string $name optional custom name.
     * @param int|null $validTime time to be valid, default 1 day.
     * @return string
     */
    public static function createToken ($name = 'csrfToken', $validTime = null)
    {
        if (! is_int($validTime))
            $validTime = 60 * 60 * 24; // token is valid for 1 day

        $csrfToken = Http::getInstance()->session()->get($name, null);
        $storeTime = Http::getInstance()->session()->get($name . 'Time', 0);

        if ((($validTime + $storeTime) <= time()) || empty($csrfToken)) {
            // Generate new csrf token.
            Http::getInstance()->session()->set($name, self::generateToken());
            Http::getInstance()->session()->set($name . 'Time', time());
        }

        return Http::getInstance()->session()->get('csrfToken');
    }

    /**
     * Safely test if token is valid.
     *
     * @param string $name Optional custom name for token.
     * @param string|null $token Don't fetch from POST variable but give the raw token here. (optional).
     *
     * @return bool
     */
    public static function validateToken ($name = 'csrfToken', $token = null)
    {
        if ($token === null) $token = Http::getInstance()->request()->paramPost($name);
        if (! is_string($token)) return false;
        return hash_equals($token, Http::getInstance()->session()->get($name));
    }

    /**
     * Generate safe new token.
     *
     * @return string
     */
    private static function generateToken ()
    {
        if (function_exists('mcrypt_create_iv'))
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
