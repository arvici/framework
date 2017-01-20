<?php
/**
 * Test Storage
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App;


abstract class TestStorage
{
    private static $storage = array();

    public function set($key, $value)
    {
        self::$storage[$key] = $value;
    }

    public function get($key)
    {
        return isset(self::$storage[$key]) ? self::$storage[$key] : null;
    }
}
