<?php

use Arvici\Heart\Config\Configuration;

/**
 * Template Configuration
 */
Configuration::define('template', function() {
    return [

        /**
         * Template Path, relative to the App path.
         * Don't add a trailing slash.
         */
        'path' => 'Template/Default',


        /**
         * Default template file.
         */
        'default' => 'Default'

    ];
});
