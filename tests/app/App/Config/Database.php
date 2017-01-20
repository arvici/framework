<?php

use Arvici\Heart\Config\Configuration;
use Arvici\Component\View\View;

/**
 * Template Configuration
 */
Configuration::define('database', function() {
    return [

        /**
         * The default fetch type to use.
         */
        'fetchType' => \Arvici\Heart\Database\Database::FETCH_ASSOC,

        /**
         * Database Connection names and configuration values.
         *
         * 'default' is used when no connection name is provided, or using SweetORM.
         */
        'connections' => [
            'default' => [
                'driver'        => 'MySQL',

                'host'          => 'localhost',
                'username'      => 'root',
                'password'      => '',
                'port'          => 3306,
                'database'      => 'arvici_test'
            ],



            'incomplete' => [
                'driver'        => 'MySQL',
            ],

            'nodriver' => [

            ]
        ],
    ];
});
