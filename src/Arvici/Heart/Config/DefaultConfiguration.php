<?php
/**
 * Default Configuration.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Heart\Config;


/**
 * This class contains the default section configuration values which will be used if the section
 * is not defined.
 * This will be interesting for upgrading your existing installation and if you are missing a configuration file.
 *
 * 'Missing' configuration files will be logged if in debug mode!
 *
 * @package Arvici\Heart\Config
 */
class DefaultConfiguration
{
    public static $cache = [
        /**
         * Enable cache.
         */
        'enabled' => true,

        /**
         * Set to true to enable cache even in non-production mode.
         */
        'forceEnabled' => false,

        /**
         * Cache Pool Configuration.
         */
        'pools' => [
            'default' => [
                'driver' => '\Stash\Driver\FileSystem',
                'options' => [
                    'path' => BASEPATH . 'tmp' . DS . 'cache' . DS,
                ]
            ],
        ],
    ];
}
