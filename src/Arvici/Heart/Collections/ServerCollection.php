<?php
/**
 * ServerCollection
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Collections;

/**
 * Server Collection
 *
 * @package Arvici\Heart\Collections
 */
class ServerCollection extends DataCollection
{
    private static $HttpPrefix = "HTTP_";
    private static $HttpCustomHeaders = array(
        'CONTENT_TYPE',
        'CONTENT_LENGTH',
        'CONTENT_MD5'
    );

    /**
     * Get headers (raw array)
     *
     * @return array
     */
    public function getHeaders()
    {
        $return = array();

        foreach ($this->contents as $key => $value) {
            if (strpos($key, self::$HttpPrefix) === 0) {
                $return[substr($key, strlen(self::$HttpPrefix))] = $value; // @codeCoverageIgnore
            }

            if (in_array($key, self::$HttpCustomHeaders)) {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}