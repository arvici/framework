<?php
use Arvici\Heart\Config\Configuration as Configure;


/**
 * Config: cache
 */
Configure::define('cache', function () {
    return [

        /**
         * Enable cache.
         */
        'enabled' => true,

        /**
         * Set to true to enable cache even in non-production mode.
         */
        'forceEnabled' => true,

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
});
